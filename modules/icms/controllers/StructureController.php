<?php

namespace app\modules\icms\controllers;

use app\models\Tree;
use Yii;
use yii\filters\AccessControl;
use app\models\Module;
use app\modules\icms\widgets\GreenLine;

class StructureController extends \app\components\controller\Backend
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
            ['url' => ['structure/index'], 'title' => 'Список страниц'],
        ];

        return $this->render('index');
    }

    public function actionAdd()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['structure/index'], 'title' => 'Список страниц'],
            ['title' => 'Создание страницы'],
        ];
        $model = new Tree();
        $model->scenario = 'add';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->pid == 1) {
                $url = '/' . $model->name;
            } else {
                $parentPage = Tree::findOne($model->pid);
                $url = $parentPage['url'] . '/' . $model->name;
            }

            if (Tree::find()->andWhere(['url' => $url])->exists() === false) {
                $model->save();
                GreenLine::show();
                return $this->redirect(['structure/edit', 'id' => $model->id]);
            } else {
                $model->addError('name', 'Поле должно быть уникальным!');
            }
        }

        $model->pid = Yii::$app->request->get('id_page', null);

        return $this->render('add', [
                    'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $page = Tree::findOne($id);
        if (is_null($page)) {
            return $this->redirect(['structure/add']);
        }

        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['structure/index'], 'title' => 'Список страниц'],
            ['title' => 'Редактирование страницы'],
        ];
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['id_page' => $page->id]];

        $moduleCurrent = $page->module;
        if ($page->load(Yii::$app->request->post()) && $page->validate()) {
            if (\Yii::$app->user->can('developer')) {
                $moduleTreeId = Yii::$app->request->post('moduleTreeId', false);
                if ($moduleTreeId !== false && !is_null($moduleCurrent)) {
                    $moduleCurrent->tree_id = null;
                    $moduleCurrent->url = '';
                    $moduleCurrent->save(false);
                    $moduleCurrent = null;
                }
                if (($moduleTreeId && $moduleTreeId !== Module::DEFAULT_MODULE) || (!is_null($moduleCurrent) && $moduleTreeId != $moduleCurrent->id)) {
                    $module = Module::findOne($moduleTreeId);
                    $module->tree_id = $page->id;
                    $module->url = preg_replace('/(^\/)/', '', $page->url) . '/';
                    $module->save(false);
                    $moduleCurrent = $module;
                }
            }
            $url = '/';
            if (empty($page->name) === false) {
                $parentPage = Tree::findOne($page->pid);
                $url = $parentPage['url'] . '/' . $page->name;
            }
            $checkPage = Tree::find()->andWhere(['!=', 'id', $id])->andWhere(['url' => $url])->exists();
            if (!$checkPage) {
                $page->save();
                $page->saveFiles();
                GreenLine::show();
                return $this->refresh();
            } else {
                $page->addError('name', 'Поле должно быть уникальным!');
            }
        }

        if (!is_null($moduleCurrent)) {
            $moduleId = $moduleCurrent->id;
        } else {
            $moduleId = Module::DEFAULT_MODULE;
        }

        return $this->render('edit', [
                    'model' => $page,
                    'moduleId' => $moduleId
        ]);
    }

}
