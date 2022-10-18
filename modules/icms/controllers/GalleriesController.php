<?php

namespace app\modules\icms\controllers;

use app\models\GalleryCategorie;
use app\models\Gallery;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\modules\icms\widgets\GreenLine;

class GalleriesController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'gallery', 'gallery_categorie_add', 'gallery_categorie_edit', 'gallery_add', 'gallery_edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'gallery_categorie_add', 'gallery_categorie_edit', 'gallery', 'gallery_add', 'gallery_edit'],
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
        $gallery_categorie_id = Yii::$app->request->get('id', 0);

        Yii::$app->view->params['breadCrumbs']['crumbs'] = \app\components\IcmsHelper::getBreadCrumbs(
                        'galleries/index', $gallery_categorie_id != 0 ? GalleryCategorie::findOne($gallery_categorie_id) : null, 'parent', 'pid', 'name', 'Галереи', 'id'
        );
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['gallery_categorie_id' => $gallery_categorie_id, 'pid' => $gallery_categorie_id]];

        return $this->render('index');
    }

    public function actionCategorie_add()
    {
        $model = new GalleryCategorie();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->redirect(['galleries/categorie_edit', 'id' => $model->id]);
        }
        $model->pid = \Yii::$app->getRequest()->get('pid', 0);
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['galleries/index'], 'title' => 'Список галерей'],
        ];
        return $this->render('categorie_add', ['model' => $model]);
    }

    public function actionCategorie_edit($id)
    {
        $model = GalleryCategorie::findOne($id);
        if (is_null($model) === true) {
            return $this->redirect(['galleries/index']);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->refresh();
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = \app\components\IcmsHelper::getBreadCrumbs(
                        'galleries/index', $id != 0 ? GalleryCategorie::findOne($id) : null, 'parent', 'pid', 'name', 'Галереи', 'id'
        );
        Yii::$app->view->params['breadCrumbs']['crumbs'][] = ['url' => '', 'title' => 'Редактирование'];

        return $this->render('categorie_edit', ['model' => $model]);
    }

    public function actionGallery_add()
    {
        $model = new Gallery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->redirect(['galleries/gallery_edit', 'id' => $model->id]);
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['galleries/index'], 'title' => 'Список галерей'],
        ];
        $model->gallery_categorie_id = \Yii::$app->getRequest()->get('gallery_categorie_id', 0);
        return $this->render('gallery_add', ['model' => $model]);
    }

    public function actionGallery_edit($id)
    {
        $gallery = Gallery::findOne($id);
        if (is_null($gallery) === true) {
            return $this->redirect(['galleries/gallery_add']);
        }
        if ($gallery->load(Yii::$app->request->post()) && $gallery->save()) {
            $gallery->saveFiles();
            GreenLine::show();
            return $this->refresh();
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = ArrayHelper::merge(\app\components\IcmsHelper::getBreadCrumbs(
                                'galleries/index', $gallery->categorie, 'parent', 'pid', 'name', 'Каталог', 'id'
                        ), [['title' => $gallery->name]]);
        return $this->render('gallery_edit', ['model' => $gallery]);
    }

}
