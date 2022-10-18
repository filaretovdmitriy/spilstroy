<?php

namespace app\modules\icms\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Slider;
use app\models\Slide;
use app\modules\icms\widgets\GreenLine;

class SlidersController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'slides', 'add', 'edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit', 'slides'],
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
            ['url' => ['sliders/index'], 'title' => 'Список слайдеров'],
        ];

        return $this->render('index');
    }

    public function actionSlides($slider_id)
    {
        $this->layout = 'innerPjax';
        $slider = Slider::findOne($slider_id);

        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['slider_id' => $slider->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['sliders/index'], 'title' => 'Список слайдеров'],
            ['title' => $slider->name],
        ];
        if (!is_null($slider)) {
            return $this->render('slides');
        } else {
            return $this->redirect(['sliders/index']);
        }
    }

    public function actionSlider_add()
    {
        $model = new Slider();
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['sliders/index'], 'title' => 'Список слайдеров'],
            ['title' => 'Создание слайдера'],
        ];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['sliders/slider_edit', 'id' => $model->id]);
        }

        return $this->render('slider', ['model' => $model]);
    }

    public function actionSlider_edit($id)
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['sliders/index'], 'title' => 'Список слайдеров'],
            ['title' => 'Редактирование слайдера'],
        ];
        $model = Slider::findOne($id);
        if (!is_null($model)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                GreenLine::show();
                return $this->refresh();
            }
            return $this->render('slider', ['model' => $model]);
        } else {
            return $this->redirect(['sliders/slider_add']);
        }
    }

    public function actionSlide_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['sliders/index'], 'title' => 'Список слайдеров'],
            ['title' => 'Создание слайда'],
        ];
        $model = new Slide();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->redirect(['sliders/slide_edit', 'id' => $model->id]);
        }

        $model->slider_id = \Yii::$app->getRequest()->get('slider_id', 0);

        return $this->render('slide', ['model' => $model]);
    }

    public function actionSlide_edit($id)
    {
        $slide = Slide::findOne($id);
        $slider = $slide->slider;
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['slider_id' => $slider->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['sliders/index'], 'title' => 'Список слайдеров'],
            ['title' => 'Редактирование слайда'],
        ];
        if (!is_null($slide)) {
            if ($slide->load(Yii::$app->request->post()) && $slide->save()) {
                $slide->saveFiles();
                GreenLine::show();
                return $this->refresh();
            }
            return $this->render('slide', ['model' => $slide]);
        } else {
            return $this->redirect(['sliders/slide_add']);
        }
    }

}
