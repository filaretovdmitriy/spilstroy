<?php

namespace app\modules\icms\controllers;

use app\models\ContentCategorie;
use app\models\Content;
use Yii;
use yii\filters\AccessControl;
use app\modules\icms\widgets\GreenLine;

class ContentsController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'content', 'content_categotie_add', 'content_categotie_edit', 'content_add', 'content_edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'content_categotie_add', 'content_categotie_edit', 'content', 'content_add', 'content_edit'],
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
            ['title' => 'Список категорий'],
        ];

        return $this->render('index');
    }

    public function actionContent($id)
    {
        $this->layout = 'innerPjax';
        $contentCategorie = ContentCategorie::findOne($id);
        if (is_null($contentCategorie) === true) {
            return $this->redirect(['contents/index']);
        }
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['content_categorie_id' => $contentCategorie->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['contents/index'], 'title' => 'Список категорий'],
            ['title' => $contentCategorie->name],
        ];

        return $this->render('contents');
    }

    public function actionCategotie_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['contents/index'], 'title' => 'Список категорий'],
            ['title' => 'Создание категории'],
        ];
        $model = new ContentCategorie();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                GreenLine::show();
                return $this->redirect(['contents/categorie_edit', 'id' => $model->id]);
            }
        }

        return $this->render('categorie', ['model' => $model]);
    }

    public function actionCategorie_edit($id)
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['contents/index'], 'title' => 'Список категорий'],
            ['title' => 'Редактирование категории'],
        ];
        $model = ContentCategorie::findOne($id);
        if (is_null($model) === true) {
            return $this->redirect(['contents/index']);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                GreenLine::show();
                return $this->refresh();
            }
        }
        return $this->render('categorie', ['model' => $model]);
    }

    public function actionContent_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['contents/index'], 'title' => 'Список категорий'],
            ['title' => 'Создание контента'],
        ];
        $model = new Content();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->saveFiles();
                GreenLine::show();
                return $this->redirect(['contents/content_edit', 'id' => $model->id]);
            }
        }
        $model->content_categorie_id = Yii::$app->getRequest()->get('content_categorie_id', 0);
        return $this->render('content', ['model' => $model]);
    }

    public function actionContent_edit($id)
    {
        $content = Content::findOne($id);
        $contentCategorie = $content->contentCategorie;
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['content_categorie_id' => $contentCategorie->id]];
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['contents/index'], 'title' => 'Список категорий'],
            ['title' => 'Редактирование контента'],
        ];
        if (is_null($content) === true) {
            return $this->redirect(['contents/content_add']);
        }
        if ($content->load(Yii::$app->request->post()) && $content->save()) {
            $content->saveFiles();
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('content', ['model' => $content]);
    }

}
