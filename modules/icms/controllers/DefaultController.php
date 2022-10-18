<?php

namespace app\modules\icms\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\icms\forms\LoginForm;
use app\components\IcmsHelper;

class DefaultController extends \app\components\controller\Backend
{

    public $layout = 'login';

    public function actions()
    {

        return [
            'error' => [
                'class' => 'app\components\IcmsErrorAction',
                'layout' => Yii::$app->user->isGuest ? 'error' : 'inner'
            ]
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'logout', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?', 'manager'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest === false) {
            return $this->redirect(['default/dashboard']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) === true && $model->login() === true) {
            \app\models\UserLog::addLog(\Yii::$app->user->can('developer'));
            Yii::$app->session->setFlash('welcome', Yii::$app->user->identity->name);
            return $this->redirect(['default/dashboard']);
        }

        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionLost_password()
    {
        if (\Yii::$app->user->isGuest === false) {
            return $this->redirect(['default/dashboard']);
        }
        $model = new \app\modules\icms\forms\LostPasswordForm();
        if ($model->load(Yii::$app->request->post()) === true) {
            $model->changePassword();
            Yii::$app->session->setFlash('success', 'Новый пароль выслан Вам на email');
            return $this->redirect(['default/dashboard']);
        } else {
            return $this->render('lost_password', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['default/index']);
    }

    public function actionDashboard()
    {
        $this->layout = 'inner';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => \Yii::$app->name],
        ];

        $userLog = \app\models\UserLog::find()
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(11);
        if (Yii::$app->user->can('developer') === false) {
            $userLog->andWhere(['developer_only' => 0]);
        }

        $clear = \Yii::$app->request->post('clear', false);
        if ($clear !== false && Yii::$app->user->can('developer')) {
            switch ($clear) {
                case 'cms':
                    IcmsHelper::deleteCasheFolder(Yii::getAlias('@webroot/icms/assets'));
                    \app\modules\icms\widgets\GreenLine::show('Кеш cms сброшен');
                    break;
                case 'site':
                    IcmsHelper::deleteCasheFolder(Yii::getAlias('@webroot/assets'));
                    \app\modules\icms\widgets\GreenLine::show('Кеш сайта сброшен');
                    break;
                case 'framework':
                    IcmsHelper::deleteCasheFolder(Yii::getAlias('@runtime'));
                    \app\modules\icms\widgets\GreenLine::show('Кеш фрейворка отчищен');
                    break;
                case 'images':
                    IcmsHelper::deleteCasheFiles(Yii::getAlias('@image_cache'));
                    \app\modules\icms\widgets\GreenLine::show('Кеш изображений отчищен');
                    break;
                case 'all':
                    IcmsHelper::deleteCasheFolder(Yii::getAlias('@webroot/icms/assets'));
                    IcmsHelper::deleteCasheFolder(Yii::getAlias('@webroot/assets'));
                    IcmsHelper::deleteCasheFolder(Yii::getAlias('@runtime'));
                    IcmsHelper::deleteCasheFiles(Yii::getAlias('@image_cache'));
                    \app\modules\icms\widgets\GreenLine::show('Весь кеш сброшен');
                    break;
            }
            return $this->refresh();
        }

        $cacheSizes = [
            'cms' => IcmsHelper::getDirecrotySize(Yii::getAlias('@webroot/icms/assets')),
            'site' => IcmsHelper::getDirecrotySize(Yii::getAlias('@webroot/assets')),
            'framework' => IcmsHelper::getDirecrotySize(Yii::getAlias('@runtime')),
            'images' => IcmsHelper::getDirecrotySize(Yii::getAlias('@image_cache')),
        ];

        return $this->render('dashboard', [
                    'userLog' => $userLog->all(),
                    'baseInfo' => IcmsHelper::getBaseInfo(),
                    'cacheSizes' => $cacheSizes,
        ]);
    }

    public function actionPhp_info()
    {
        if (Yii::$app->user->can('developer') === false) {
            return $this->redirect(['default/index']);
        }

        phpinfo();
    }

}
