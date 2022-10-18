<?php

namespace app\modules\icms\controllers;

use app\models\Feedback;
use Yii;
use yii\filters\AccessControl;
use app\modules\icms\widgets\GreenLine;

class FeedbacksController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'add', 'edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['feedbacks/index'], 'title' => 'Список отзывов'],
        ];

        return $this->render('index');
    }

    public function actionAdd()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['feedbacks/index'], 'title' => 'Список отзывов'],
            ['url' => '', 'title' => 'Создание отзыва'],
        ];

        $model = new Feedback();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['feedbacks/edit', 'id' => $model->id]);
        }

        return $this->render('feedback', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $model = Feedback::findOne($id);

        if (is_null($model) === true) {
            $this->redirect(['feedbacks/add']);
        }

        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['feedbacks/index'], 'title' => 'Список отзывов'],
            ['url' => '', 'title' => 'Редактирование отзыва'],
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('feedback', ['model' => $model]);
    }

}
