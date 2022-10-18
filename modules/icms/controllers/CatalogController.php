<?php

namespace app\modules\icms\controllers;

use app\models\CatalogCategorie;
use app\models\Catalog;
use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\Prop;
use app\models\PropsGroup;
use app\modules\icms\widgets\GreenLine;

class CatalogController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'props', 'props_add', 'catalog_categorie_add', 'catalog_categorie_edit', 'catalog_add', 'catalog_edit'],
                'rules' => [
                    [
                        'actions' => ['index', 'props', 'props_add', 'catalog_categorie_add', 'catalog_categorie_edit', 'catalog_add', 'catalog_edit'],
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
        $catalog_id = Yii::$app->request->get('id', 0);

        Yii::$app->view->params['breadCrumbs']['crumbs'] = \app\components\IcmsHelper::getBreadCrumbs(
                        'catalog/index', $catalog_id != 0 ? CatalogCategorie::findOne($catalog_id) : null, 'parent', 'pid', 'name', 'Каталог', 'id'
        );
        Yii::$app->view->params['adminMenuDropDown'] = ['pids' => ['catalog_categorie_id' => $catalog_id, 'pid' => $catalog_id]];
        return $this->render('index');
    }

    public function actionProps()
    {
        $this->layout = 'innerPjax';

        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['catalog/index'], 'title' => 'Каталог'],
            ['title' => 'Свойства']
        ];
        return $this->render('props');
    }

    public function actionCategorie_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [['url' => ['catalog/index'], 'title' => 'Каталог'], ['title' => 'Добавить категорию']];
        $model = new CatalogCategorie();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->redirect(['catalog/categorie_edit', 'id' => $model->id]);
        }

        $model->pid = \Yii::$app->getRequest()->get('pid', 0);
        return $this->render('categorie_add', ['model' => $model]);
    }

    public function actionCategorie_edit($id)
    {
        $model = CatalogCategorie::findOne($id);
        if (is_null($model) === true) {
            return $this->redirect(['catalog/index']);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            $seoGenerator = new \app\components\SeoGenerator($model);
            $seoGenerator->getParameters();
            $seoGenerator->genereate();
            return $this->refresh();
        }
        $props = Prop::getGroupedAsArray();
        $catalogCategorieProps = ArrayHelper::map($model->catalogCategorieProps, 'props_id', 'props_id');

        Yii::$app->view->params['breadCrumbs']['crumbs'] = ArrayHelper::merge(\app\components\IcmsHelper::getBreadCrumbs(
                                'catalog/index', $model, 'parent', 'pid', 'name', 'Каталог', 'id'
                        ), [['title' => 'Редактировать']]);

        return $this->render('categorie_edit', [
                    'model' => $model,
                    'catalogCategorieProps' => $catalogCategorieProps,
                    'propInfos' => $props
        ]);
    }

    public function actionCatalog_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [['url' => ['catalog/index'], 'title' => 'Каталог'], ['title' => 'Добавить товар']];
        $model = new Catalog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            GreenLine::show();
            return $this->redirect(['catalog/catalog_edit', 'id' => $model->id]);
        }
        $model->catalog_categorie_id = \Yii::$app->getRequest()->get('catalog_categorie_id', 0);
        return $this->render('catalog_add', ['model' => $model]);
    }

    public function actionCatalog_edit($id)
    {
        $catalog = Catalog::findOne($id);
        if (is_null($catalog) === true) {
            return $this->redirect(['catalog/catalog_add']);
        }

        if ($catalog->load(Yii::$app->request->post()) && $catalog->validate()) {
            if (($catalog->propsCode->load(Yii::$app->request->post())) && ($catalog->propsCode->validate())) {
                $catalog->propsCode->save($catalog->id);
            }

            $catalog->save();
            $catalog->saveFiles();
            GreenLine::show();
            Yii::$app->getSession()->setFlash('success', 'Элемент сохранен!');
            $catalog = Catalog::findOne($id);
        }
        $allPropsArray = [];
        $allProps = Prop::find()->all();
        if (count($allProps) > 0) {
            foreach ($allProps as $pr) {
                $allPropsArray[$pr->alias]['name'] = $pr->name;
                $allPropsArray[$pr->alias]['prop_type_id'] = $pr->prop_type_id;
                $allPropsArray[$pr->alias]['prop_type_list_id'] = $pr->prop_type_list_id;
                $allPropsArray[$pr->alias]['props_groups_id'] = empty($pr->props_groups_id) ? 0 : $pr->props_groups_id;
                $allPropsArray[$pr->alias]['values'] = ArrayHelper::map($pr->propsValues, 'id', 'name');
            }
        }

        Yii::$app->view->params['breadCrumbs']['crumbs'] = ArrayHelper::merge(\app\components\IcmsHelper::getBreadCrumbs(
                                'catalog/index', $catalog->catalog_categorie_id != 0 ? CatalogCategorie::findOne($catalog->catalog_categorie_id) : null, 'parent', 'pid', 'name', 'Каталог', 'id'
                        ), [['title' => $catalog->name]]);

        $propGroups = [0 => 'Без категории'] + PropsGroup::getNamesAsArray();

        return $this->render('catalog_edit', [
                    'model' => $catalog,
                    'allPropsArray' => $allPropsArray,
                    'propGroups' => $propGroups
        ]);
    }

    public function actionProp_add()
    {
        $model = new Prop();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['catalog/prop_edit', 'id' => $model->id]);
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [['url' => ['catalog/index'], 'title' => 'Каталог'], ['url' => ['catalog/props'], 'title' => 'Свойства'], ['title' => 'Добавить свойство']];
        return $this->render('prop_add', ['model' => $model]);
    }

    public function actionProp_edit($id)
    {
        $props = Prop::findOne($id);
        if (is_null($props) === true) {
            return $this->redirect(['catalog/prop_add']);
        }
        if ($props->load(Yii::$app->request->post()) && $props->save()) {
            GreenLine::show();
            Yii::$app->getSession()->setFlash('success', 'Элемент сохранен!');
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [['url' => ['catalog/index'], 'title' => 'Каталог'], ['url' => ['catalog/props'], 'title' => 'Свойства'], ['title' => 'Редактировать свойство']];
        return $this->render('prop_edit', ['model' => $props]);
    }

    public function actionProp_groups()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [['url' => ['catalog/index'], 'title' => 'Каталог'], ['title' => 'Список групп свойств']];
        return $this->render('groups');
    }

    public function actionProp_group_add()
    {
        $model = new PropsGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['catalog/prop_group_edit', 'id' => $model->id]);
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['catalog/index'], 'title' => 'Каталог'],
            ['url' => ['catalog/prop_groups'], 'title' => 'Список групп свойств'],
            ['title' => 'Добавить группу свойств']
        ];
        return $this->render('group', ['model' => $model]);
    }

    public function actionProp_group_edit($id)
    {
        $group = PropsGroup::findOne($id);
        if (is_null($group) === true) {
            return $this->redirect(['catalog/prop_group_add']);
        }
        if ($group->load(Yii::$app->request->post()) && $group->save()) {
            GreenLine::show();
            Yii::$app->getSession()->setFlash('success', 'Элемент сохранен!');
        }
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['catalog/index'], 'title' => 'Каталог'],
            ['url' => ['catalog/prop_groups'], 'title' => 'Список групп свойств'],
            ['title' => 'редактировать группу свойств']
        ];
        return $this->render('group', ['model' => $group]);
    }

}
