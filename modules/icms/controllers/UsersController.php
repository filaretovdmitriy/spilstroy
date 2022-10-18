<?php

namespace app\modules\icms\controllers;

use Yii;
use app\models\User;
use yii\filters\AccessControl;
use app\modules\icms\widgets\GreenLine;

class UsersController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'add', 'edit', 'edit_password'],
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit', 'edit_password'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['users/index'], 'title' => 'Список пользователей'],
        ];

        return $this->render('index');
    }

    public function actionAdd()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['users/index'], 'title' => 'Список пользователей'],
            ['url' => '', 'title' => 'Создание нового пользователя'],
        ];
        $model = new User(['scenario' => 'default']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['users/edit', 'id' => $model->id]);
        }

        return $this->render('add', ['roles' => User::getRolesAsArray(), 'model' => $model]);
    }

    public function actionEdit($id)
    {
        $user = User::findOne($id);
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['users/index'], 'title' => 'Список пользователей'],
            ['url' => '', 'title' => 'Редактирование пользователя'],
        ];
        if (is_null($user) === true) {
            return $this->redirect(['users/add']);
        }
        $user->scenario = 'edit';

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('edit', ['roles' => User::getRolesAsArray(), 'model' => $user]);
    }

    public function actionEdit_password($id)
    {
        $user = User::findOne($id);
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['users/index'], 'title' => 'Список пользователей'],
            ['url' => ['users/edit', 'id' => $user->id], 'title' => $user->name ?: $user->login],
            ['url' => '', 'title' => 'Редактирование пароля'],
        ];
        if (is_null($user) === true) {
            return $this->redirect(['users/index']);
        }
        $user->scenario = 'editPassword';

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            GreenLine::show('Пароль сохранен');
            return $this->redirect(['users/edit', 'id' => $user->id]);
        }
        return $this->render('password_edit', ['model' => $user]);
    }

}
