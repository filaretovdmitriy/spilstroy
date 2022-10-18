<?php

namespace app\modules\icms\controllers;

use Yii;
use app\models\AdminMenu;
use app\models\Key;
use app\models\Module;
use yii\filters\AccessControl;
use app\modules\icms\widgets\GreenLine;
use app\components\IcmsHelper;

class DeveloperController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'menu_add', 'menu_edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'menu_add', 'menu_edit', 'dumps', 'dumps_download', 'dumps_remove'],
                        'allow' => true,
                        'roles' => ['developer'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => 'Список пунктов меню'],
        ];

        return $this->render('index');
    }

    public function actionMenu_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['developer/index'], 'title' => 'Список пунктов меню'],
            ['title' => 'Создание пункта меню'],
        ];
        $model = new AdminMenu();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                GreenLine::show();
                return $this->redirect(['developer/menu_edit', 'id' => $model->id]);
            }
        }

        return $this->render('menu', ['model' => $model]);
    }

    public function actionMenu_edit($id)
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['developer/index'], 'title' => 'Список пунктов меню'],
            ['title' => 'Редактирование пункта меню'],
        ];
        $adminMenu = AdminMenu::findOne($id);
        if (!is_null($adminMenu)) {
            if ($adminMenu->load(Yii::$app->request->post()) && $adminMenu->save()) {
                GreenLine::show();
                return $this->refresh();
            }
            return $this->render('menu', ['model' => $adminMenu]);
        } else {
            return $this->redirect(['developer/menu_add']);
        }
    }

    public function actionKeys()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => 'Список ключей'],
        ];

        return $this->render('keys');
    }

    public function actionKey_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['developer/keys'], 'title' => 'Список ключей'],
            ['title' => 'Создание ключа'],
        ];
        $model = new Key();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                GreenLine::show();
                return $this->redirect(['developer/key_edit', 'id' => $model->id]);
            }
        }

        return $this->render('key', ['model' => $model]);
    }

    public function actionKey_edit($id)
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['developer/keys'], 'title' => 'Список ключей'],
            ['title' => 'Редактирование ключа'],
        ];
        $key = Key::findOne($id);
        if (!is_null($key)) {
            if ($key->load(Yii::$app->request->post()) && $key->save()) {
                GreenLine::show();
                return $this->refresh();
            }
            return $this->render('key', ['model' => $key]);
        } else {
            return $this->redirect(['developer/key_add']);
        }
    }

    public function actionModules()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => 'Список модулей'],
        ];

        return $this->render('modules');
    }

    public function actionModule_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['developer/modules'], 'title' => 'Список модулей'],
            ['title' => 'Создание модуля'],
        ];
        $model = new Module();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                GreenLine::show();
                return $this->redirect(['developer/module_edit', 'id' => $model->id]);
            }
        }

        return $this->render('module', ['model' => $model]);
    }

    public function actionModule_edit($id)
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['developer/modules'], 'title' => 'Список модулей'],
            ['title' => 'Редактирование модуля'],
        ];
        $module = Module::findOne($id);
        if (!is_null($module)) {
            if ($module->load(Yii::$app->request->post()) && $module->save()) {
                GreenLine::show();
                return $this->refresh();
            }
            return $this->render('module', ['model' => $module]);
        } else {
            return $this->redirect(['developer/module_add']);
        }
    }

    public function actionDumps()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => 'Резервное копирование'],
        ];


        $info = \yii\helpers\ArrayHelper::merge(
                        ['for_source' => 0, 'for_base' => 0, 'for_upload' => 0, 'clear' => 0], \Yii::$app->request->post('dump', [])
        );
        if ($info !== false && ($info['for_source'] || $info['for_base'] || $info['for_upload'])) {

            $zipName = \app\components\Backup::run($info['for_source'], $info['for_upload'], $info['for_base'], $info['clear']);

            GreenLine::show('Дамп создан - ' . $zipName);
            \Yii::$app->session->setFlash('LAST_CREATED_NAME', $zipName);
            return $this->refresh();
        }

        $dumpsPath = \Yii::getAlias('@backups/');
        $dumpsFiles = glob($dumpsPath . '*.zip');

        $dumps = [];
        foreach ($dumpsFiles as $dumpFile) {
            $fileName = str_replace([$dumpsPath, '.zip'], '', $dumpFile);
            $parseName = explode('_', $fileName);
            $dateParse = explode('-', $parseName[0]);
            $date = array_shift($dateParse);
            $dumps[] = [
                'date' => date('d.m.Y H:i:s', strtotime($date . ' ' . implode(':', $dateParse))),
                'name' => $fileName,
                'size' => filesize($dumpFile),
                'has_source' => strpos($parseName[1], 'source') !== false,
                'has_upload' => strpos($parseName[1], 'upload') !== false,
                'has_base' => strpos($parseName[1], 'base') !== false,
            ];
        }

        $cacheSizes = [
            'cms' => IcmsHelper::getDirecrotySize(Yii::getAlias('@webroot/icms/assets')),
            'site' => IcmsHelper::getDirecrotySize(Yii::getAlias('@webroot/assets')),
            'framework' => IcmsHelper::getDirecrotySize(Yii::getAlias('@runtime')),
            'images' => IcmsHelper::getDirecrotySize(Yii::getAlias('@image_cache')),
        ];

        usort($dumps, function($a, $b) {
            $aDate = strtotime($a['date']);
            $bDate = strtotime($b['date']);
            if ($aDate == $bDate) {
                return 0;
            }
            return ($aDate > $bDate) ? -1 : 1;
        });
        return $this->render('dumps', [
                    'cacheSizes' => $cacheSizes,
                    'dumps' => $dumps,
                    'lastCreate' => \Yii::$app->session->getFlash('LAST_CREATED_NAME', false)
        ]);
    }

    public function actionDump_download()
    {
        $fileName = \Yii::$app->request->get('file', false);

        if ($fileName === false) {
            throw new \yii\web\NotFoundHttpException();
        }
        $fileName .= '.zip';
        $path = \Yii::getAlias('@backups/');

        if (is_file($path . $fileName) === false) {
            throw new \yii\web\NotFoundHttpException();
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . mime_content_type($path . $fileName));
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path . $fileName));
        readfile($path . $fileName);

        exit(0);
    }

    public function actionDump_remove()
    {
        $fileName = \Yii::$app->request->get('file', false);

        if ($fileName === false) {
            throw new \yii\web\NotFoundHttpException();
        }
        $fileName .= '.zip';
        $path = \Yii::getAlias('@backups/');

        if (is_file($path . $fileName) === false) {
            throw new \yii\web\NotFoundHttpException();
        }

        unlink($path . $fileName);

        GreenLine::show('Удален - ' . $fileName);
        return $this->redirect(['developer/dumps']);
    }

}
