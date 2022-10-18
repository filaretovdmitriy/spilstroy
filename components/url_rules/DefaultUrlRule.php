<?php

namespace app\components\url_rules;

use Yii;
use app\models\Tree;
use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class DefaultUrlRule extends BaseObject implements UrlRuleInterface
{

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $params = [];
        if (empty($pathInfo) === false) {
            $findPage = Tree::find()->andWhere(['status' => Tree::STATUS_ACTIVE]);

            if (is_null($manager->module) === false && (empty($this->route) === false || $pathInfo == preg_replace('/\/+$/', '', $manager->module->url))) {
                $route = $manager->module->route;
                $findPage->andWhere(['id' => $manager->module->tree_id]);
            } else {
                $route = 'site/page';
                $params['page'] = $pathInfo;
                $findPage->andWhere(['url' => '/' . $pathInfo]);
            }
            $page = $findPage->one();

            if (is_null($page) === true) {
                return false;
            }

            Yii::$app->urlManager->pageId = $page->id;

        } else {
            $route = 'site/index';
            \Yii::$app->urlManager->pageId = Tree::findOne(['url' => '/'])->id;
        }

        return [$route, $params];
    }

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'site/page') {
            $url = preg_replace('/^\//', '', $params['page']);
        } elseif($route === 'site/index') {
            return '/';
        } else {
            $module = \app\models\Module::findOne(['route' => $route]);
            if (is_null($module) === true) {
                return false;
            }
            $url = preg_replace('/\/+$/', '', $module->url);
        }

        if (isset($params['page']) === true && is_numeric($params['page']) === false) {
            unset($params['page']);
        }

        if (empty($params) === false) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

}
