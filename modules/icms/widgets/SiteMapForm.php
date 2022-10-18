<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use app\components\IcmsHelper;
use yii\helpers\ArrayHelper;

class SiteMapForm extends Widget
{

    public $models;
    public $siteName = '';
    public $pathXML = '';
    private $modelsInfo = [];
    private $urls = [];
    private $changefreq = [
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never',
    ];

    private function getHostFromRobots()
    {
        $robotsURL = IcmsHelper::hasFile(\yii\helpers\Url::home(true) . 'robots.txt');
        $robotsFullPath = \Yii::getAlias('@webroot/robots.txt');
        if (!$robotsURL) {
            return false;
        }
        $handle = @fopen($robotsFullPath, "r");
        while (($buffer = fgets($handle)) !== false) {
            if (strpos($buffer, '#') !== false) {
                continue;
            }
            if (strpos($buffer, 'Host:') !== false) {
                if (IcmsHelper::isHttps()) {
                    $siteName = 'https://';
                } else {
                    $siteName = 'http://';
                }
                $siteName .= trim(str_replace('Host:', '', $buffer));
                fclose($handle);
                return $siteName;
            }
        }
        fclose($handle);
        return false;
    }

    private function getLoc(&$element, &$modelOptions)
    {
        $route = isset($modelOptions['route']) ? $modelOptions['route'] : 'site/page';
        $params = [$route];

        foreach ($modelOptions['url'] as $paramName => $param) {
            if (is_array($param)) {
                $params[$paramName] = $element->{$param[0]}->{$param[1]};
            } else {
                $params[$paramName] = $element->{$param};
            }
        }

        $configFront = ArrayHelper::merge(
                        require(\Yii::getAlias('@app/config/web.php')), require(\Yii::getAlias('@app/config/frontend.php'))
        );
        $config = $configFront['components']['urlManager'];
        unset($config['class']);
        $urlManager = new \app\components\UrlManager($config);
        $urlManager->setBaseUrl('/');
        $url = $urlManager->createUrl($params);

        return $this->siteName . '/' . preg_replace('/(^\/)|(\/+$)/', '', $url);
    }

    private function generateUrl($modelName, $changeref, $index = false)
    {
        if ($index === false) {
            $modelOptions = $this->modelsInfo[$modelName];
        } else {
            $modelOptions = $this->modelsInfo[$modelName][$index];
        }
        $elements = $modelName::find()->andWhere($modelOptions['condition'])->all();

        foreach ($elements as $element) {
            $this->urls[] = [
                'loc' => $this->getLoc($element, $modelOptions),
                'lastmod' => date(DATE_W3C, $element->updated_at),
                'changefreq' => $changeref,
                'priority' => isset($modelOptions['priority']) ? $modelOptions['priority'] : 1,
            ];
        }
    }

    public function init()
    {
        parent::init();
        foreach ($this->models as $model) {
            if (!method_exists($model, 'siteMap')) {
                throw new \Exception('Метод siteMap не найден в классе ' . $model);
            }
            $info = $model::siteMap();
            if (ArrayHelper::isAssociative($info)) {
                $this->modelsInfo[$model] = $info;
            } else {
                if (count($info) === 1) {
                    $info = $info[0];
                    $this->modelsInfo[$model] = $info;
                } else {
                    foreach ($info as $infoElement) {
                        $this->modelsInfo[$model][] = $infoElement;
                    }
                }
            }
        }
        if (empty($this->pathXML)) {
            $this->pathXML = \Yii::getAlias('@webroot');
        } else {
            if (!file_exists($this->pathXML) || !is_writable($this->pathXML)) {
                throw new \Exception('Не найден путь или нет доступа к ' . $this->pathXML);
            }
        }
    }

    public function run()
    {
        $activeModules = \Yii::$app->request->post('sitemap-active', false);
        if ($activeModules !== false && count($activeModules) > 0) {
            if (empty($this->siteName)) {
                $this->siteName = $this->getHostFromRobots();
            }

            $sitemapChange = \Yii::$app->request->post('sitemap-change', false);
            if ($sitemapChange === false) {
                throw new Exception('Нет настроек частоты обновления');
            }

            foreach ($activeModules as $name => $on) {

                if (is_array($on)) {
                    foreach ($on as $key => $value) {
                        $this->generateUrl($name, $sitemapChange[$name][$key], $key);
                    }
                } else {
                    $this->generateUrl($name, $sitemapChange[$name]);
                }
            }

            $this->saveXML();
            $hasRobots = IcmsHelper::hasFile(\yii\helpers\Url::home(true) . 'robots.txt');
            $hasSitemapInRobots = IcmsHelper::fileHasString(\Yii::getAlias('@webroot/robots.txt'), 'Sitemap:');
            if ($hasRobots === true && $hasSitemapInRobots === false) {
                \Yii::$app->session->setFlash('error', 'Файл sitemap.xml не указан в файле robots.txt!');
            }
            \Yii::$app->session->setFlash('message', 'Файл sitemap.xml сгенерирован');
        }


        return $this->render('site_map_form', ['models' => $this->modelsInfo, 'changefreq' => $this->changefreq]);
    }

    private function saveXML()
    {
        $dateCreate = date('d.m.Y H:i:s');
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!-- created automatically by ICMS {$dateCreate} -->
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        foreach ($this->urls as $url) {
            $loc = IcmsHelper::xmlSpecialChars($url['loc']);
            $changefreq = $this->changefreq[$url['changefreq']];
            $xml .= "
    <url>
        <loc>{$loc}</loc>
        <lastmod>{$url['lastmod']}</lastmod>
        <changefreq>{$changefreq}</changefreq>
        <priority>{$url['priority']}</priority>
    </url>";
        }
        $xml .= '
</urlset>';
        file_put_contents($this->pathXML . 'sitemap.xml', $xml);
        chmod($this->pathXML . 'sitemap.xml', 0755);
    }

}
