<?php

namespace app\modules\icms\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Parameter;
use yii\web\UploadedFile;
use app\modules\icms\widgets\GreenLine;
use app\models\ParameterValue;

class ParametersController extends \app\components\controller\Backend
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
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => 'Параметры сайта'],
        ];

        return $this->render('parameters');
    }

    public function actionAdd()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['parameters/index'], 'title' => 'Параметры'],
            ['title' => 'Новый параметр'],
        ];

        $model = new Parameter();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['parameters/edit', 'id' => $model->id]);
        }

        return $this->render('parameter', [
                    'model' => $model,
                    'value' => new \app\models\ParameterValue(),
        ]);
    }

    public function actionEdit($id)
    {
        $model = Parameter::findOne($id);

        if (is_null($model) === true) {
            $this->redirect(['parameters/add']);
        }

        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['parameters/index'], 'title' => 'Параметры'],
            ['title' => 'Редактировать параметр'],
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = UploadedFile::getInstance($model, 'value');
            if ($file && $model->type == Parameter::TYPE_IMAGE) {
                $model->saveFiles();
            }
            if ($file && $model->type == Parameter::TYPE_FILE) {
                $model->saveFiles(['value' => $model::TYPE_FILE_FILE]);
            }
            GreenLine::show();
            return $this->refresh();
        }

        $value = new ParameterValue();
        $value->parameter_id = $model->id;

        return $this->render('parameter', [
                    'model' => $model,
                    'value' => $value,
        ]);
    }

    public function actionValue_add()
    {
        Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;
        $result = ['success' => false];

        $newValue = new ParameterValue();
        if ($newValue->load(Yii::$app->getRequest()->post()) && $newValue->save()) {
            $result['success'] = true;
        } else {
            $result['errors'] = $newValue->errors;
        }

        return $result;
    }

}
