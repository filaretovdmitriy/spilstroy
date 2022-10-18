<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;
use yii\base\InvalidConfigException;

class UrlManager extends \yii\web\UrlManager
{

    public $pageId = null;
    public $module = null;

    public function init()
    {
        \Yii::setAlias('@image_cache', '@webroot/resize/cache');
        \Yii::setAlias('@image_cache/web', '@web/resize/cache');

        \Yii::setAlias('@upload', '@webroot/upload/icms');
        \Yii::setAlias('@upload/images', '@upload/images');
        \Yii::setAlias('@upload/files', '@upload/files');

        \Yii::setAlias('@upload/web', '@web/upload/icms');
        \Yii::setAlias('@images', '@upload/web/images');
        \Yii::setAlias('@files', '@upload/web/files');

        $request = \Yii::$app->getRequest();

        $pathInfo = preg_replace('/\/+$/', '', $request->getPathInfo());

        $this->module = $this->findModule($pathInfo);

        parent::init();
    }

    private function _getUrlRule($route, $pattern, $module) {
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';

        if (empty($pattern) === false) {
            $pattern = '/' . $module->url . $pattern;
        } else {
            $pattern = '/' . preg_replace('/(\/$)/', '', $module->url);
        }
        
        $rule = ['route' => $route];
        if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $pattern, $matches)) {
            $rule['verb'] = explode(',', $matches[1]);
            // rules that do not apply for GET requests should not be use to create urls
            if (!in_array('GET', $rule['verb'])) {
                $rule['mode'] = UrlRule::PARSING_ONLY;
            }
            $pattern = $matches[4];
        }
        $rule['pattern'] = $pattern;
        $rule['class'] = url_rules\UrlRule::class;
        $rule['pageId'] = $module->tree_id;
        
        return Yii::createObject(array_merge($this->ruleConfig, $rule));
    }

    /**
     * Builds URL rule objects from the given rule declarations.
     * @param array $rules the rule declarations. Each array element represents a single rule declaration.
     * Please refer to [[rules]] for the acceptable rule formats.
     * @return UrlRuleInterface[] the rule objects built from the given rule declarations
     * @throws InvalidConfigException if a rule declaration is invalid
     */
    protected function buildRules($rules)
    {
        $compiledRules = [];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = ['route' => $rule];
                if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
                    $rule['verb'] = explode(',', $matches[1]);
                    // rules that do not apply for GET requests should not be use to create urls
                    if (!in_array('GET', $rule['verb'])) {
                        $rule['mode'] = UrlRule::PARSING_ONLY;
                    }
                    $key = $matches[4];
                }
                $rule['pattern'] = $key;
            }

            if (is_array($rule) === true) {
                if (isset($rule['routes']) === true && is_array($rule['routes']) === true) {
                    $first = true;
                    foreach ($rule['routes'] as $pattern => $route) {
                        if ($first === true) {
                            $module = \app\models\Module::findOne(['route' => $route]);
                            if (is_null($module) === true) {
                                throw new \yii\base\InvalidConfigException("Route {$route} not found in modules table");
                            }
                        }
                        
                        $rule = $this->_getUrlRule($route, $pattern, $module);

                        if (!$rule instanceof UrlRuleInterface) {
                            throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
                        }
                        $compiledRules[] = $rule;
                        $first = false;
                    }
                    continue;
                }

                $rule = Yii::createObject(array_merge($this->ruleConfig, $rule));
            }
            if (!$rule instanceof UrlRuleInterface) {
                throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $compiledRules[] = $rule;
        }
        
        return $compiledRules;
    }

    /**
     * Ищет модуль по URL
     * @param string $currentUrl - URL для поиска модуля
     * @return /app/models/Modules найденый модуль или null, если модуля не найдено
     */
    public function findModule($currentUrl)
    {
        $parseUrl = explode('/', $currentUrl);
        $modules = \app\models\Module::find()->andWhere(['!=', 'url', ''])->all();
        $level = count($parseUrl);

        for ($i = 0; $i < $level; $i++) {
            foreach ($modules as $module) {
                if ($module->url === $currentUrl . '/') {
                    return $module;
                }
            }
            $currentUrl = preg_replace('/\/[^\/]+$/', '', $currentUrl);
        }

        return null;
    }

}
