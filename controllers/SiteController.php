<?php

namespace app\controllers;

use Yii;
use app\models\Catalog;
use app\models\Banner;
use app\models\CatalogCategorie;
use app\models\CatalogProp;
use app\models\CatalogSku;
use app\models\CatalogSkuPropsValue;
use app\forms\SearchForm;
use app\models\ContentCategorie;
use app\models\Gallery;
use app\models\GalleryCategorie;
use app\models\Prop;
use app\models\Content;
use yii\helpers\ArrayHelper;
use app\components\IcmsHelper;
use yii\data\Pagination;

class SiteController extends \app\components\controller\Frontend
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'index';
        $Catalog = Catalog::find()
                        ->andWhere([
                            'is_popular' => 1,
                            'status' => Catalog::STATUS_ACTIVE,
                        ])->all();
        
        return $this->render('index',['catalog'=>$Catalog]);
    }

    public function actionPage()
    {
        return $this->render('textpage');
    }

    public function actionSitemap()
    {
        return $this->render('sitemap');
    }

    public function actionNews()
    {
        $categorieInfo = ContentCategorie::findOne(1);

        if (empty($categorieInfo)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $content = $categorieInfo->getContents()
                ->andWhere(['status' => Content::STATUS_ACTIVE])
                ->andWhere(['<=', 'g_date', date('Y-m-d H:i:s')]);

        $pages = new Pagination([
            'totalCount' => $content->count(),
            'pageSize' => $categorieInfo->in_list,
        ]);
        $pages->pageSizeParam = 'limit';
        $contents = $content
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy(['g_date' => SORT_DESC])
                ->all();

        return $this->render('news', [
                    'contents' => $contents,
                    'pages' => $pages,
        ]);
    }

    public function actionArticles()
    {
        $categorieInfo = ContentCategorie::findOne(1);

        if (empty($categorieInfo)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $content = $categorieInfo->getContents()
                ->andWhere(['status' => Content::STATUS_ACTIVE])
                ->andWhere(['<=', 'g_date', date('Y-m-d H:i:s')]);

        $pages = new Pagination([
            'totalCount' => $content->count(),
            'pageSize' => $categorieInfo->in_list,
        ]);
        $pages->pageSizeParam = 'limit';
        $contents = $content
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy(['g_date' => SORT_DESC])
                ->all();

        return $this->render('articles', [
                    'contents' => $contents,
                    'pages' => $pages,
        ]);
    }

    public function actionNews_element()
    {
        $alias = Yii::$app->request->get('alias');
        $content = Content::find()
                        ->andWhere([
                            'alias' => $alias,
                            'status' => Content::STATUS_ACTIVE,
                        ])->one();
        if (empty($content)) {
            throw new \yii\web\NotFoundHttpException();
        }
        $categorie = ContentCategorie::findOne($content->content_categorie_id);
        $this->bread = ArrayHelper::merge(IcmsHelper::getBreadCrumbsTree($this->view->tree, true), ['label' => $content->name]);
        $this->view->title = $content->name;
        if (!empty($content->title_seo)) {
            $this->view->title = $content->title_seo;
        }
        $this->view->keywords = $content->keywords_seo;
        $this->view->description = $content->description_seo;
        return $this->render('news_element', ['content' => $content, 'categorie' => $categorie]);
    }

    public function actionArticles_element()
    {
        $alias = Yii::$app->request->get('alias');
        $content = Content::find()
                        ->andWhere([
                            'alias' => $alias,
                            'status' => Content::STATUS_ACTIVE,
                        ])->one();
        if (empty($content)) {
            throw new \yii\web\NotFoundHttpException();
        }
        $categorie = ContentCategorie::findOne($content->content_categorie_id);
        $this->bread = ArrayHelper::merge(IcmsHelper::getBreadCrumbsTree($this->view->tree, true), ['label' => $content->name]);
        $this->view->title = $content->name;
        if (!empty($content->title_seo)) {
            $this->view->title = $content->title_seo;
        }
        $this->view->keywords = $content->keywords_seo;
        $this->view->description = $content->description_seo;
        return $this->render('articles_element', ['content' => $content, 'categorie' => $categorie]);
    }

    public function actionGallery()
    {
        $gallerys = GalleryCategorie::find()
                ->andWhere(['status' => GalleryCategorie::STATUS_ACTIVE]);
        $pages = new Pagination([
            'totalCount' => $gallerys->count(),
            'pageSizeParam' => 'limit',
            'pageSize' => 12
        ]);
        $gallerys->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy(['sort' => SORT_ASC]);

        return $this->render('gallery', [
                    'gallerys' => $gallerys->all(),
                    'pages' => $pages
        ]);
    }

    public function actionGallery_element()
    {
        $categorieId = Yii::$app->request->get('gallery_categorie_id');
        $categorie = GalleryCategorie::find()->andWhere([
                    'id' => $categorieId,
                    'status' => GalleryCategorie::STATUS_ACTIVE
                ])->one();
        if (empty($categorie)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $gallery = Gallery::find()
                        ->andWhere([
                            'gallery_categorie_id' => $categorieId,
                            'status' => Gallery::STATUS_ACTIVE
                        ])->all();
        $this->bread = ArrayHelper::merge(IcmsHelper::getBreadCrumbsTree($this->view->tree, true), ['label' => $categorie->name]);

        $this->view->title = $categorie->name;
        if (!empty($categorie->title_seo)) {
            $this->view->title = $categorie->title_seo;
        }
        $this->view->keywords = $categorie->keywords_seo;
        $this->view->description = $categorie->description_seo;

        return $this->render('gallery_element', [
                    'gallery' => $gallery,
                    'gallery_categorie' => $categorie
        ]);
    }

    public function actionCatalog()
    {
        
        $categories = CatalogCategorie::find()
                ->andWhere(['status' => CatalogCategorie::STATUS_ACTIVE, 'pid' => 0])
                ->orderBy(['sort' => SORT_ASC, 'name' => SORT_ASC])
                ->all();

        return $this->render('catalog_categories', [
                    'categories' => $categories
        ]);
    }

    public function actionCatalog_categorie()
    {
        $this->layout = 'catalog';
        $catalog_categorie_alias = \Yii::$app->request->get('catalog_categorie_alias');
        $get_prop = Yii::$app->request->get('prop');

        $catalog_categorie = CatalogCategorie::find()->andWhere([
                    'alias' => $catalog_categorie_alias,
                    'status' => CatalogCategorie::STATUS_ACTIVE,
                ])->one();

        if (empty($catalog_categorie)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $this->bread = ArrayHelper::merge(IcmsHelper::getBreadCrumbsTree($this->view->tree, true), ['label' => $catalog_categorie->name]);
        $catalog = Catalog::find()
                ->andWhere([
            'catalog_categorie_id' => $catalog_categorie->id,
            'status' => Catalog::STATUS_ACTIVE,
        ]);

        

       

        $countCatalog = clone $catalog;
        $minPrice = $countCatalog->min('price');
        $maxPrice = $countCatalog->max('price');
        $price_min_get = Yii::$app->request->get('price_min', $minPrice);
        $price_max_get = Yii::$app->request->get('max_price', $maxPrice);
        
        
        
        if (!empty($price_max_get) && !empty($get_prop)) {
            foreach (array_keys($get_prop) as $prop_key) {
                $propInfo = Prop::findOne($prop_key);
                if ($propInfo->is_sku) {
                    if (($propInfo['prop_type_list_id'] == 1) || ($propInfo['prop_type_list_id'] == 3)) {
                        $elems = ArrayHelper::map(CatalogSkuPropsValue::find()->andWhere('props_id=:props_id and value=:value', ['props_id' => $prop_key, 'value' => $get_prop[$prop_key]])->all(), 'catalog_sku_id', 'catalog_sku_id');
                    }
                    if ($propInfo['prop_type_list_id'] == 2) {
                        $elems = ArrayHelper::map(CatalogSkuPropsValue::find()->andWhere(['props_id' => $prop_key, 'value' => $get_prop[$prop_key]])->all(), 'catalog_sku_id', 'catalog_sku_id');
                    }
                    if (($propInfo['prop_type_list_id'] == 0) && ($propInfo['prop_type_id'] == 3)) {
                        $elems = ArrayHelper::map(CatalogSkuPropsValue::find()->andWhere('props_id=:props_id and cast(value as DECIMAL(10,6))>=:value1 and cast(value as DECIMAL(10,6))<=:value2', [
                                            'props_id' => $prop_key, 'value1' => $get_prop[$prop_key]['min'], 'value2' => $get_prop[$prop_key]['max']
                                        ])->all(), 'catalog_sku_id', 'catalog_sku_id');
                    }
                    if (isset($elems) && !empty($elems)) {
                        $goodIds = ArrayHelper::map(CatalogSku::find()->andWhere(['id' => $elems, 'status' => 1])->all(), 'catalog_id', 'catalog_id');
                        $catalog->andFilterWhere(['id' => $goodIds]);
                    }
                } else {
                    
                    if (($propInfo['prop_type_list_id'] == 1) || ($propInfo['prop_type_list_id'] == 3)) {
                        $elems = ArrayHelper::map(CatalogProp::find()->andWhere('props_id=:props_id and value=:value', ['props_id' => $prop_key, 'value' => $get_prop[$prop_key]])->all(), 'catalog_id', 'catalog_id');
                        $catalog->andFilterWhere(['in', 'id', $elems]);
                    }
                    if ($propInfo['prop_type_list_id'] == 2) {
                        $elems = ArrayHelper::map(CatalogProp::find()->andWhere(['props_id' => $prop_key, 'value' => $get_prop[$prop_key]])->all(), 'catalog_id', 'catalog_id');
                        $catalog->andFilterWhere(['in', 'id', $elems]);
                    }
                    if (($propInfo['prop_type_list_id'] == 0) && ($propInfo['prop_type_id'] == 3)) {
                        $elems = ArrayHelper::map(CatalogProp::find()->andWhere('props_id=:props_id and cast(value as DECIMAL(10,6))>=:value1 and cast(value as DECIMAL(10,6))<=:value2', ['props_id' => $prop_key, 'value1' => $get_prop[$prop_key]['min'], 'value2' => $get_prop[$prop_key]['max']])->all(), 'catalog_id', 'catalog_id');
                        $catalog->andFilterWhere(['in', 'id', $elems]);
                    }
                }
            }
        }

        if (!empty($price_min_get) && (!empty($price_max_get))) {
            $catalog->andWhere('(price>=:price_start and price<=:price_max)', ['price_start' => intval($price_min_get), 'price_max' => intval($price_max_get)]
            );
        }

        

        $sort = new \yii\data\Sort([
            'attributes' => [
                'name' => [
                    'default' => SORT_ASC,
                    'label' => 'По названию',
                ],
                'price' => [
                    'default' => SORT_ASC,
                    'label' => 'По цене',
                ],
                'is_popular'=>[
                    'default' => SORT_ASC,
                    'label' => 'По популярности',
                ],
                'sort'
            ],
            'defaultOrder' => ['sort' => SORT_ASC]
        ]);

        $countQuery = clone $catalog;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->pageSizeParam = 'limit';
        $pages->defaultPageSize = 15;
        $catalog = $catalog
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy($sort->orders)
                ->all();
               

        $this->view->h1 = $catalog_categorie->name;
        $this->view->title = $catalog_categorie->title_seo ?: $catalog_categorie->name;
        $this->view->keywords = $catalog_categorie->keywords_seo;
        $this->view->description = $catalog_categorie->description_seo;


        //min & max for filter
        $props_min_max = [];
        foreach ($catalog_categorie->catalogCategorieProps as $elem) {
            if ($elem->props->is_filter == 1) {
                if ($elem->props->prop_type_list_id == 0) {
                    if (($elem->props->prop_type_id == 3) || ($elem->props->prop_type_id == 1)) {

                        $elems = ArrayHelper::map(Catalog::find()->andWhere('catalog_categorie_id=:catalog_categorie_id', ['catalog_categorie_id' => $catalog_categorie->id])->all(), 'id', 'id');
                        $min = CatalogProp::find()->andWhere('props_id=:props_id', ['props_id' => $elem->props_id])->andFilterWhere(['in', 'catalog_id', $elems])->min('cast(value as UNSIGNED)');
                        $max = CatalogProp::find()->andWhere('props_id=:props_id', ['props_id' => $elem->props_id])->andFilterWhere(['in', 'catalog_id', $elems])->max('cast(value as UNSIGNED)');

                        $props_min_max[$elem->props_id]['min'] = $min;
                        $props_min_max[$elem->props_id]['max'] = $max;
                    }
                }
            }
        }

        return $this->render('catalog_categorie', [
                    'catalog' => $catalog,
                    'catalog_categorie' => $catalog_categorie,
                    'pages' => $pages,
                    'total_count' => $pages->totalCount,
                    'price_min_get' => $price_min_get,
                    'price_max_get' => $price_max_get,
                    'minPrice' => $minPrice,
                    'maxPrice' => $maxPrice,
                    'props_min_max' => $props_min_max,
                    'get_prop' => $get_prop,
                    'sort' => $sort
        ]);
    }

    public function actionCatalog_element()
    {
        $catalog_id = \Yii::$app->request->get('catalog_id');
        $catalog = Catalog::findOne($catalog_id);
        if (empty($catalog)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $catalogRelated = Catalog::find()->andWhere([
            'catalog_categorie_id' => $catalog->catalog_categorie_id,
            'status' => Catalog::STATUS_ACTIVE,
        ])->andWhere(['<>','id', $catalog->id])->all();

        $catalogAlternate = $catalog->relatedGoods;
        
        $this->layout = "textpage";
        $this->view->title = $catalog->name;
        if (!empty($catalog->title_seo)) {
            $this->view->title = $catalog->title_seo;
        }
        $this->view->keywords = $catalog->keywords_seo;
        $this->view->description = $catalog->description_seo;

        $this->bread = ArrayHelper::merge(
                        IcmsHelper::getBreadCrumbsTree($this->view->tree, true), IcmsHelper::getBreadCrumbsLabel($this->view->tree->url, $catalog->categorie, 'parent', 'pid', 'name', '', 'alias', 0, true), [['label' => $catalog->name]]
        );

        $propsList = ArrayHelper::map(Prop::find()->all(), 'alias', 'name'); //свойства через alias
        $propsListId = ArrayHelper::map(Prop::find()->all(), 'id', 'name'); //свойства через id


        $catalogSku = CatalogSku::find()->andWhere(['catalog_id' => $catalog_id])->all();

        $catalogHasSku = !empty($catalogSku);

        $catalogSkuProps = CatalogSkuPropsValue::find()->andWhere(['in', 'catalog_sku_id', ArrayHelper::map($catalogSku, 'id', 'id')])->all();
        $skuGrid = [];
        foreach ($catalogSkuProps as $elem) {
            $skuGrid[$elem->props_id][$elem->value] = $elem->propsValue->name;
        }
        return $this->render('catalog_element', [
                    'catalog' => $catalog,
                    'propsList' => $propsList,
                    'propsListId' => $propsListId,
                    'catalogSku' => $catalogSku,
                    'skuGrid' => $skuGrid,
                    'catalogHasSku' => $catalogHasSku,
                    'catalogRelated' => $catalogRelated,
                    'catalogAlternate' => $catalogAlternate,
        ]);
    }

    public function actionCatalog_search()
    {
        $model = new SearchForm();
        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            $catalog = Catalog::find()
                    ->andWhere(['status' => Catalog::STATUS_ACTIVE])
                    ->andWhere([
                        'OR',
                        ['like', 'name', $model->searchText],
                        ['like', 'article', $model->searchText]
                    ])
                    ->orFilterWhere(['like', 'content', $model->searchText]);

            $pages = new Pagination([
                'totalCount' => $catalog->count(),
                'pageSizeParam' => 'limit',
                'defaultPageSize' => 15
            ]);
            $catalog = $catalog
                    ->offset($pages->offset)
                    ->limit($pages->limit)
                    ->orderBy(['price' => SORT_ASC])
                    ->all();
            return $this->render('catalog_search', [
                        'catalog' => $catalog,
                        'pages' => $pages,
                        'total_count' => $pages->totalCount,
                        'searchText' => $model->searchText
            ]);
        }
    }

}
