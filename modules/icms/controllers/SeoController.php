<?php

namespace app\modules\icms\controllers;

use Yii;
use app\components\WhoIs;
use yii\filters\AccessControl;
use app\components\IcmsHelper;
use yii\helpers\Url;
use app\modules\icms\widgets\GreenLine;

class SeoController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['seo/index'], 'title' => 'SEO'],
        ];

        $whoIsText = (new WhoIs())->goWhois(Yii::$app->request->serverName);
        $absoluteHomeUrl = Url::home(true);

        $hasRobots = IcmsHelper::hasFile($absoluteHomeUrl . 'robots.txt');
        $hasFavicon = IcmsHelper::hasFile($absoluteHomeUrl . 'favicon.ico');
        $hasSitemapInRobots = IcmsHelper::fileHasString(\Yii::getAlias('@webroot/robots.txt'), 'Sitemap:');
        $hasSitemap = IcmsHelper::hasFile($absoluteHomeUrl . 'sitemap.xml');

        $redirectConfigPath = Yii::getAlias('@app/config/redirects.php');
        $redirectCount = count(require($redirectConfigPath));

        $metrics = \app\models\Key::getGroup(12);

        return $this->render('index', [
                    'hasRobots' => $hasRobots,
                    'hasFavicon' => $hasFavicon,
                    'hasSitemap' => $hasSitemap,
                    'hasSitemapInRobots' => $hasSitemapInRobots,
                    'whoIsText' => $whoIsText,
                    'redirectCount' => $redirectCount,
                    'metrics' => $metrics
        ]);
    }

    public function actionRobots()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['seo/index'], 'title' => 'SEO'],
            ['title' => 'Редактирование robots.txt'],
        ];

        $fileContent = '';
        $fileName = \Yii::getAlias('@webroot/robots.txt');
        $hasFile = IcmsHelper::hasFile(Url::home(true) . 'robots.txt');

        if (Yii::$app->request->post('save-button', false) !== false) {

            $robots = fopen($fileName, 'w+');
            fwrite($robots, Yii::$app->request->post('robotsText', false));
            fclose($robots);

            if (!$hasFile) {
                chmod($fileName, 0755);
                GreenLine::show('Файл robots.txt создан и сохранен');
                \Yii::$app->session->setFlash('message', 'Файл robots.txt создан и сохранен');
            } else {
                GreenLine::show('Файл robots.txt сохранен');
                \Yii::$app->session->setFlash('message', 'Файл robots.txt сохранен');
            }
            $hasFile = true;
        }

        if ($hasFile) {
            if (file_exists($fileName) && is_readable($fileName)) {
                $fh = fopen($fileName, "r");
                $fileContent = fread($fh, filesize($fileName));
                fclose($fh);
            }
        }


        return $this->render('robots', ['fileContent' => $fileContent]);
    }

    public function actionSite_map()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['seo/index'], 'title' => 'SEO'],
            ['title' => 'Генерация sitemap.xml'],
        ];

        return $this->render('sitemap');
    }

    public function actionSite_map_upload()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['seo/index'], 'title' => 'SEO'],
            ['title' => 'Загрузка sitemap.xml'],
        ];

        $file = \yii\web\UploadedFile::getInstanceByName('new_sitemap');
        if (is_null($file) === false && $file->error === UPLOAD_ERR_OK) {
            $xml = new \XMLReader();
            $xml->open($file->tempName);

            $xml->setParserProperty(\XMLReader::VALIDATE, true);

            if ($xml->isValid() === true && $file->extension === 'xml') {

                $file->saveAs(\Yii::getAlias('@webroot/sitemap.xml'));
                GreenLine::show('Файл sitemap.xml загружен!');
                return $this->redirect(['seo/index']);
            }

            \Yii::$app->getSession()->setFlash('SITEMAP_ERROR');
            return $this->refresh();
        }

        return $this->render('sitemap_upload');
    }

    public function actionRedirects()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['seo/index'], 'title' => 'SEO'],
            ['title' => 'Редиректы'],
        ];

        $redirectConfigPath = Yii::getAlias('@app') . '/config/redirects.php';

        if (\Yii::$app->request->post('save-button', false) !== false) {
            $editRedirects = \Yii::$app->request->post('redirects', []);
            $newRedirects = \Yii::$app->request->post('redirectsNew', []);
            $saveRedirects = \yii\helpers\ArrayHelper::merge($editRedirects, $newRedirects);

            $file = \yii\web\UploadedFile::getInstanceByName('file');
            if (is_null($file) === false && $file->error === UPLOAD_ERR_OK) {
                $fileRedirects = [];
                $fileContent = file_get_contents($file->tempName);
                $isNormal = strpos($fileContent, PHP_EOL) !== false;

                $fileRows = explode($isNormal ? PHP_EOL : "\r", $fileContent);

                foreach ($fileRows as $row) {
                    if (empty($row) === true) {
                        continue;
                    }
                    $data = explode(';', $row);
                    $from = $data[0];
                    $to = $data[1];
                    $code = isset($data[2]) === true ? (int) $data[2] : null;
                    if (empty($code) === true || in_array($code, [301, 302]) === false) {
                        $code = 301;
                    }

                    $fileRedirects['from'][] = $from;
                    $fileRedirects['to'][] = $to;
                    $fileRedirects['code'][] = $code;
                }

                if (Yii::$app->getRequest()->post('overload', false) !== false) {
                    $saveRedirects = $fileRedirects;
                } else {
                    $saveRedirects = \yii\helpers\ArrayHelper::merge($saveRedirects, $fileRedirects);
                }
            }

            $resultRedirects = [];
            if (empty($saveRedirects) === false) {
                foreach ($saveRedirects['from'] as $key => $from) {
                    $from = preg_replace('/(^\/)|(\/+$)/', '', $from);
                    if (empty($from) || empty($saveRedirects['to'][$key]) || $from === $saveRedirects['to'][$key]) {
                        continue;
                    }
                    $to = preg_replace('/\/+$/', '', $saveRedirects['to'][$key]);
                    $resultRedirects['/' . $from] = [
                        $to ?: '/',
                        (int) $saveRedirects['code'][$key],
                    ];
                }
            }
            $arrayString = var_export($resultRedirects, true);
            file_put_contents($redirectConfigPath, <<<PHP
<?php
/* This file is generated automatically ICMS */
return $arrayString;
PHP
            );

            GreenLine::show('Редиректы сохранены');
            Yii::$app->session->setFlash('message', 'Редиректы сохранены');
            return $this->refresh();
        }

        $redirects = require($redirectConfigPath);

        return $this->render('redirects', [
                    'redirects' => $redirects
        ]);
    }

    public function actionMetrics()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['seo/index'], 'title' => 'SEO'],
            ['title' => 'Метрики, информеры'],
        ];

        return $this->render('metrics');
    }

}
