<?php

namespace app\modules\icms\controllers;

use app\models\Map;
use app\models\MapMark;
use Yii;
use yii\filters\AccessControl;
use app\modules\icms\widgets\GreenLine;

class MapsController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'map_add', 'map_edit', 'mark_add', 'mark_edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'map_add', 'map_edit', 'mark_add', 'mark_edit'],
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
            ['url' => ['maps/index'], 'title' => 'Список карт'],
        ];

        return $this->render('index');
    }

    public function actionMarks($map_id)
    {
        $this->layout = 'innerPjax';
        $maps = Map::findOne($map_id);
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['map_id' => $maps->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['maps/index'], 'title' => 'Список карт'],
            ['title' => $maps->name],
        ];
        if (is_null($maps) === false) {
            return $this->render('marks');
        } else {
            return $this->redirect(['maps/index']);
        }
    }

    public function actionMap_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['maps/index'], 'title' => 'Список карт'],
            ['title' => 'Создание каты'],
        ];
        $model = new Map();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['maps/map_edit', 'id' => $model->id]);
        }

        return $this->render('map', ['model' => $model]);
    }

    public function actionMap_edit($id)
    {
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['map_id' => $id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['maps/index'], 'title' => 'Список карт'],
            ['title' => 'Редактирование каты'],
        ];
        $model = Map::findOne($id);
        if (is_null($model) === false) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                GreenLine::show();
                return $this->refresh();
            }
            return $this->render('map', ['model' => $model]);
        } else {
            return $this->redirect(['maps/map_add']);
        }
    }

    public function actionMark_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['maps/index'], 'title' => 'Список карт'],
            ['title' => 'Создание метки'],
        ];
        $model = new MapMark();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->redirect(['maps/mark_edit', 'id' => $model->id]);
        }
        if ($model->map_id == 0) {
            $model->map_id = Yii::$app->getRequest()->get('map_id', 0);
        }
        return $this->render('mark', ['model' => $model]);
    }

    public function actionMark_edit($id)
    {
        $mark = MapMark::findOne($id);

        if (is_null($mark) === false) {
            if ($mark->load(Yii::$app->request->post()) && $mark->save()) {
                $mark->saveFiles();
                GreenLine::show();
                return $this->refresh();
            }
            $maps = $mark->map;
            Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['map_id' => $maps->id]];
            Yii::$app->view->params['breadCrumbs']['crumbs'] = [
                ['url' => ['maps/index'], 'title' => 'Список карт'],
                ['url' => ['maps/marks', 'map_id' => $maps->id], 'title' => $maps->name],
                ['title' => 'Редактирование метки'],
            ];
            return $this->render('mark', ['model' => $mark]);
        } else {
            return $this->redirect(['maps/mark_add']);
        }
    }

}
