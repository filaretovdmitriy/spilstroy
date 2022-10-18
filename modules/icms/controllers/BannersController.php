<?php

namespace app\modules\icms\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\BannerGroup;
use app\models\Banner;
use app\modules\icms\widgets\GreenLine;

class BannersController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'group', 'add', 'edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit', 'group'],
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
            ['title' => 'Группы баннеров'],
        ];

        return $this->render('index');
    }

    public function actionGroup($id)
    {
        $this->layout = 'innerPjax';
        $bannerGroup = BannerGroup::findOne($id);

        if (is_null($bannerGroup) === true) {
            return $this->redirect(['banners/index']);
        }

        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['group_id' => $bannerGroup->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['banners/index'], 'title' => 'Группы баннеров'],
            ['title' => $bannerGroup->name],
        ];
        return $this->render('banners');
    }

    public function actionGroup_add()
    {
        $model = new BannerGroup();
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['banners/index'], 'title' => 'Группы баннеров'],
            ['title' => 'Создание группы баннеров'],
        ];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['banners/group_edit', 'id' => $model->id]);
        }

        return $this->render('group', ['model' => $model]);
    }

    public function actionGroup_edit($id)
    {
        $model = BannerGroup::findOne($id);
        if (is_null($model) === true) {
            return $this->redirect(['banners/index']);
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['banners/index'], 'title' => 'Группы баннеров'],
            ['title' => 'Редактирование группы баннеров'],
        ];
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['group_id' => $model->id]];
        if ($model->load(Yii::$app->request->post())) {
            GreenLine::show();
            $model->save();
            return $this->refresh();
        }
        return $this->render('group', ['model' => $model]);
    }

    public function actionBanner_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['banners/index'], 'title' => 'Группы баннеров'],
            ['title' => 'Создание баннера'],
        ];
        $model = new Banner();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->type == Banner::TYPE_IMAGE) {
                $model->saveFiles();
            }
            if ($model->type == Banner::TYPE_FLASH) {
                $model->saveFiles(['file' => $model::TYPE_FILE_FILE]);
            }
            GreenLine::show();
            return $this->redirect(['banners/banner_edit', 'id' => $model->id]);
        }

        $model->banner_group_id = \Yii::$app->getRequest()->get('banner_group_id', 0);

        return $this->render('banner', ['model' => $model]);
    }

    public function actionBanner_edit($id)
    {
        $banner = Banner::findOne($id);
        if (is_null($banner) === true) {
            return $this->redirect(['banners/banner_add']);
        }
        $bannerGroup = $banner->group;
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['group_id' => $bannerGroup->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['banners/index'], 'title' => 'Группы баннеров'],
            ['title' => 'Редактирование баннера'],
        ];
        if ($banner->load(Yii::$app->request->post()) && $banner->save()) {
            if ($banner->type == Banner::TYPE_IMAGE) {
                $banner->saveFiles();
            }
            if ($banner->type == Banner::TYPE_FLASH) {
                $banner->saveFiles(['file' => $banner::TYPE_FILE_FILE]);
            }
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('banner', ['model' => $banner]);
    }

}
