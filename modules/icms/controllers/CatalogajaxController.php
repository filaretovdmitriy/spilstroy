<?php

namespace app\modules\icms\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Catalog;
use app\models\Prop;
use yii\helpers\Html;
use app\models\CatalogCategorieProp;
use app\components\IcmsHelper;
use app\modules\icms\widgets\multi_upload\MultiUpload;

class CatalogajaxController extends Controller
{

    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax === false && $action->id != 'edit_value_save') {
            throw new \yii\web\HttpException(403, 'Только для ajax');
        }

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    public function actionEdit_value()
    {
        $propId = \Yii::$app->request->get('prop_id', false);
        $prop = Prop::findOne($propId);
        if (is_null($prop)) {
            throw new \yii\web\HttpException(500, 'Prop (ID:' . $propId . ') not found');
        }
        $valueId = \Yii::$app->request->get('value_id', false);
        if ($valueId === false) {
            $model = new \app\models\PropsValue();
            $model->props_id = $propId;
        } else {
            $model = \app\models\PropsValue::findOne($valueId);
        }
        $saveUrl = Url::to(['catalogajax/edit_value_save']);
        $js = <<<JS
$('#prop-value-modal-form').on('beforeSubmit', function () {
    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '{$saveUrl}');
    xhr.onload = function() {
        data = JSON.parse(xhr.responseText);
        if (data.success) {
            $.fancybox.close();
            $.pjax.reload('#props-values');
        }
    };
    xhr.upload.onloadstart = function() {
        $('#preloader').show();
    };
    $('#prop-value-modal-form button[type=submit]').attr('disabled', 'disabled');
    xhr.send(formData);
    return false;
});
JS;
        \Yii::$app->view->registerJs($js);
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->renderAjax('prop_value', [
                    'model' => $model
        ]);
    }

    public function actionEdit_value_save()
    {
        $result = ['success' => false];

        $valueId = \Yii::$app->request->post('value_id', false);
        if ($valueId === false) {
            $model = new \app\models\PropsValue();
        } else {
            $model = \app\models\PropsValue::findOne($valueId);
        }
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $model->saveFiles();
            $result['success'] = true;
        }
        return $result;
    }

    public function actionSku_catalog_generator()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException(403, 'Ajax only');
        }
        $categorieId = \Yii::$app->request->get('categorie_id', false);
        $model = \app\models\CatalogCategorie::findOne($categorieId);
        if (is_null($model)) {
            throw new \yii\web\HttpException(500, 'Categorie (ID:' . $categorieId . ') not found');
        }

        $props = [];
        $props_codes = \yii\helpers\ArrayHelper::map(CatalogCategorieProp::find()->andWhere(['catalog_categorie_id' => $categorieId])->all(), 'props_id', 'props_id');
        $propsList = \app\models\Prop::find()->andWhere(['in', 'id', array_keys($props_codes)])->andWhere(['is_sku' => 1, 'prop_type_id' => 4])->all();
        foreach ($propsList as $pr) {
            $props[$pr->alias]['id'] = $pr->id;
            $props[$pr->alias]['name'] = $pr->name;
            $props[$pr->alias]['props_groups_id'] = empty($pr->props_groups_id) ? 0 : $pr->props_groups_id;
            $props[$pr->alias]['values'] = \yii\helpers\ArrayHelper::map($pr->propsValues, 'id', 'name');
        }

        $runUrl = Url::to(['catalogajax/sku_categorie_generator_run']);

        $this->view->registerJs(<<<JS
$('#generate-categorie-sku').on('click', function() {
    var elem = $(this);
    elem.prop('disabled', 'disabled');
    parameters = $('#sku-categorie-parameters input').serialize();
    $('#preloader').show();
    $.post('{$runUrl}', parameters, function(data) {
        if (data.success) {
            $.gritter.add({title: 'Генерация завершена', text:'Создано торговых предложений: ' + data.count, sticky: true});
        }
        elem.removeProp('disabled');
        $('#preloader').hide();
        $.fancybox.close();
    }, 'json');
});
$('#sku-categorie-parameters').on('click', '.article-element', function() {
    enterElement = $('#article-template');
    addText = '{{' + $(this).data('prop') + '}}';
    enterElement.val(enterElement.val() + addText);
    return false;
});
JS
        );
    
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->renderAjax('sku_catalog_generator', [
                    'model' => $model,
                    'props' => $props
        ]);
    }

    public function actionSku_categorie_generator_run()
    {
        set_time_limit(-1);
        ini_set('max_execution_time', 0);

        $result = ['success' => true, 'count' => 0];

        $categorieId = \Yii::$app->request->post('categorie_id', false);
        $categorie = \app\models\CatalogCategorie::findOne($categorieId);
        if (is_null($categorie)) {
            throw new \yii\web\HttpException(500, 'Categorie (ID:' . $categorieId . ') not found');
        }
        $categorieIds = $categorie->getChildrensCategorieId([$categorieId]);

        $goods = Catalog::find()->andWhere(['catalog_categorie_id' => $categorieIds])->all();

        $articleTemplate = \Yii::$app->request->post('article-template', '');
        $parametersPost = \Yii::$app->request->post('parameters', []);
        foreach ($goods as $catalog) {

            foreach (\app\models\CatalogSku::find()->andWhere(['catalog_id' => $catalog->id])->all() as $oldSku) {
                $oldSku->delete();
            }

            $sku = $catalog->getSkuList();

            $marks = [];
            preg_match_all('/{{(.+?)}}/mi', $articleTemplate, $marks);

            $parametersSku = \app\components\IcmsHelper::generateParameters($parametersPost);

            foreach ($parametersSku as $parameters) {
                foreach ($parameters as $propName => $value) {
                    $sku->{$propName} = $value;
                }
                $article = $articleTemplate;
                foreach ($marks[1] as $mark) {
                    $parseMark = explode(':', $mark);
                    if (!isset($parameters[$parseMark[0]]) && $parseMark[0] !== 'good_id') {
                        continue;
                    }
                    if ($parseMark[0] !== 'good_id') {
                        $article = str_replace('{{' . $parseMark[0] . '}}', $parameters[$parseMark[0]], $article);
                    } else {
                        if (count($parseMark) === 1) {
                            $article = str_replace('{{good_id}}', $catalog->id, $article);
                        } else {
                            $article = str_replace('{{good_id:' . $parseMark[1] . '}}', \app\components\IcmsHelper::addZero($catalog->id, $parseMark[1]), $article);
                        }
                    }
                }
                if ($sku->validate() && $sku->save($catalog->id, null, 0, $article, \app\models\CatalogSku::STATUS_ACTIVE)) {
                    $result['count'] ++;
                }
            }
        }

        return $result;
    }
    
    public function actionSku_generator()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException(403, 'Ajax only');
        }
        $catalogId = \Yii::$app->request->get('catalog_id', false);
        $model = Catalog::findOne($catalogId);
        if (is_null($model)) {
            throw new \yii\web\HttpException(500, 'Good (ID:' . $catalogId . ') not found');
        }

        $allPropsArray = [];
        $props = $model->getProperties(true);
        foreach ($props as $pr) {
            $allPropsArray[$pr->alias]['name'] = $pr->name;
            $allPropsArray[$pr->alias]['prop_type_id'] = $pr->prop_type_id;
            $allPropsArray[$pr->alias]['prop_type_list_id'] = $pr->prop_type_list_id;
            $allPropsArray[$pr->alias]['props_groups_id'] = empty($pr->props_groups_id) ? 0 : $pr->props_groups_id;
            $allPropsArray[$pr->alias]['values'] = \yii\helpers\ArrayHelper::map($pr->propsValues, 'id', 'name');
        }

        $runUrl = Url::to(['catalogajax/sku_generator_run']);

        $this->view->registerJs(<<<JS
$('#generate-sku').on('click', function() {
    var elem = $(this);
    elem.prop('disabled', 'disabled');
    parameters = $('#sku-parameters input').serialize();
    $('#preloader').show();
    $.post('{$runUrl}', parameters, function(data) {
        if (data.success) {
            $.gritter.add({title: 'Генерация завершена', text:'Создано торговых предложений: ' + data.count});
            $.pjax.reload('#' + $('.pjax-sku-wraper').attr('id'))
        }
        elem.removeProp('disabled');
        $('#preloader').hide();
        $.fancybox.close();
    }, 'json');
});
$('#sku-parameters').on('click', '.article-element', function() {
    enterElement = $('#article-template');
    addText = '{{' + $(this).data('prop') + '}}';
    enterElement.val(enterElement.val() + addText);
    return false;
});
JS
        );

        $skuList = $model->getSkuList();
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->renderAjax('sku_generator', [
                    'model' => $model,
                    'skuList' => $skuList,
                    'allPropsArray' => $allPropsArray
        ]);
    }

    public function actionSku_generator_run()
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException(403, 'Ajax only');
        }
        $catalogId = \Yii::$app->request->post('catalog_id', false);
        $catalog = \app\models\Catalog::findOne($catalogId);
        if (is_null($catalog)) {
            throw new \yii\web\HttpException(500, 'Good (ID:' . $catalogId . ') not found');
        }

        $result = ['success' => true];
        $sku = $catalog->getSkuList();

        $articleTemplate = \Yii::$app->request->post('article-template', '');
        $marks = [];
        preg_match_all('/{{(.+?)}}/mi', $articleTemplate, $marks);

        $parametersPost = \Yii::$app->request->post('parameters', []);
        $parametersSku = \app\components\IcmsHelper::generateParameters($parametersPost);

        $result['count'] = 0;
        foreach ($parametersSku as $parameters) {
            foreach ($parameters as $propName => $value) {
                $sku->{$propName} = $value;
            }
            $article = $articleTemplate;
            foreach ($marks[1] as $mark) {
                $parseMark = explode(':', $mark);
                if (!isset($parameters[$parseMark[0]]) && $parseMark[0] !== 'good_id') {
                    continue;
                }
                if ($parseMark[0] !== 'good_id') {
                    $article = str_replace('{{' . $parseMark[0] . '}}', $parameters[$parseMark[0]], $article);
                } else {
                    if (count($parseMark) === 1) {
                        $article = str_replace('{{good_id}}', $catalog->id, $article);
                    } else {
                        $article = str_replace('{{good_id:' . $parseMark[1] . '}}', \app\components\IcmsHelper::addZero($catalog->id, $parseMark[1]), $article);
                    }
                }
            }
            if ($sku->validate() && $sku->save($catalogId, null, 0, $article, \app\models\CatalogSku::STATUS_ACTIVE)) {
                $result['count'] ++;
            }
        }

        return $result;
    }

    public function actionDelete_sku_all()
    {
        $catalogId = \Yii::$app->request->post('catalogId', false);
        $catalog = Catalog::findOne($catalogId);
        if (is_null($catalog)) {
            throw new \yii\web\HttpException(500, 'Good (ID:' . $catalogId . ') not found');
        }

        foreach ($catalog->skus as $sku) {
            $sku->delete();
        }

        return ['success' => true];
    }

    public function actionDelete_sku()
    {
        $skuId = Yii::$app->request->post('id');

        $sku = \app\models\CatalogSku::findOne($skuId);
        if (is_null($sku)) {
            throw new \yii\base\Exception('Торговое предложение не найдено', 500);
        }

        $sku->delete();

        return ['success' => true];
    }

    public function actionProp_category_multy()
    {
        $result = ['success' => 'no'];
        $propId = Yii::$app->request->post('propId');
        $propChecked = Yii::$app->request->post('propChecked');
        $categoryId = Yii::$app->request->post('categoryId');
        if ($propChecked == 'true') {
            $ccp = new CatalogCategorieProp();
            $ccp->props_id = $propId;
            $ccp->catalog_categorie_id = $categoryId;
            $ccp->save();
            $result = ['success' => 'yep'];
        }
        if ($propChecked == 'false') {
            CatalogCategorieProp::deleteAll(
                    'catalog_categorie_id=:catalog_categorie_id and props_id=:props_id', ['catalog_categorie_id' => $categoryId, 'props_id' => $propId]);
            $result = ['success' => 'yep'];
        }
        return $result;
    }

    public function actionSku()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        $catalog_id = Yii::$app->request->get('catalog_id');
        $sku_id = Yii::$app->request->get('sku_id');
        $model = \app\models\Catalog::findOne($catalog_id);

        if (is_null($model)) {
            throw new \yii\web\HttpException(500, 'Товар не найден');
        }
        $view = $this->getView();
        $view->clear();
        $view->beginPage();
        $view->head();
        $view->beginBody();
        $form = \app\modules\icms\widgets\ActiveFormIcms::begin([
                    'options' => [
                        'class' => 'forms forms-columnar',
                        'enctype' => 'multipart/form-data',
                        'id' => 'catalog-create-sku-form'
                    ]
        ]);
        $price = $article = '';
        $status = 0;
        if (!is_null($sku_id)) {
            $sku = $model->getSkuList($sku_id);
            $skuModel = \app\models\CatalogSku::findOne($sku_id);
            $price = $skuModel->price;
            $article = $skuModel->article;
            $status = $skuModel->status;
            echo Html::hiddenInput('sku_id', $sku_id);
        } else {
            $sku = $model->getSkuList();
        }
        if (count($sku) > 0) {
            $allPropsArray = [];
            $props = $model->getProperties(true);
            foreach ($props as $pr) {
                $allPropsArray[$pr->alias]['name'] = $pr->name;
                $allPropsArray[$pr->alias]['prop_type_id'] = $pr->prop_type_id;
                $allPropsArray[$pr->alias]['prop_type_list_id'] = $pr->prop_type_list_id;
                $allPropsArray[$pr->alias]['props_groups_id'] = empty($pr->props_groups_id) ? 0 : $pr->props_groups_id;
                $allPropsArray[$pr->alias]['values'] = \yii\helpers\ArrayHelper::map($pr->propsValues, 'id', 'name');
            }
            $htmlProps = [];
            foreach ($sku as $key => $prop) {
                $html = "<fieldset class='clearfix'><div class='form-group'>";
                $html .= \app\components\IcmsHelper::renderProp($sku, $key, $form, $allPropsArray);
                $html .= "</div></fieldset>";
                $htmlProps[$allPropsArray[$key]['props_groups_id']][] = $html;
            }
            $propGroups = [0 => 'Без категории'] + \app\models\PropsGroup::getNamesAsArray();

            echo '
            <fieldset class="line-box">
                <legend>Общие</legend>
                <fieldset>
                    <label>Артикул</label>
                    ' . Html::textInput('Sku[acticle]', $article, ['class' => 'width-100']) . '
                </fieldset>
                <fieldset>
                    <label>Цена</label>
                    ' . Html::textInput('Sku[price]', $price, ['class' => 'width-100']) . '
                </fieldset>
                <fieldset>
                    <div>
                    <label>Статус</label>
                    ' . \app\modules\icms\widgets\drop_down_list\DropDownList::widget([
                'name' => 'Sku[status]',
                'items' => \app\models\CatalogSku::getStatuses(),
                'selection' => $status
            ]) . '
                    </div>
                </fieldset>
            </fieldset>';
            foreach ($htmlProps as $groupId => $propsHtml) {
                ?>
                <fieldset class="line-box">
                    <legend><?= $propGroups[$groupId] ?></legend>
                    <?php
                    foreach ($propsHtml as $propHtml) {
                        echo $propHtml;
                    }
                    ?>
                </fieldset>
                <?php
            }
        }
        echo Html::hiddenInput('catalogId', $catalog_id);
        echo Html::beginTag('div', ['class' => 'modal-buttons-wrap']);
        echo Html::submitButton('Сохранить', ['class' => 'button']);
        echo Html::endTag('biv');

        $js = <<<JS
$('#catalog-create-sku-form').on('beforeSubmit', function () {
    formInfo = $(this).serialize();
    $.post('/icms/catalogajax/save_sku', formInfo, function(data) {
        $.gritter.add({title: 'Сохранено', text: 'Торговое предложение сохранено'});
        pjax_id = $('.pjax-sku-wraper').attr('id');
        $.pjax.reload('#' + pjax_id);
        $.fancybox.close();
    }, 'json');

    return false;
});
JS;

        $view->registerJs($js);

        $form->end();
        $view->endBody();
        $view->endPage(true);
    }

    public function actionSave_sku()
    {
        $result = ['success' => true];
        $catalogId = Yii::$app->request->post('catalogId');
        $skuId = Yii::$app->request->post('sku_id');

        $catalog = \app\models\Catalog::findOne($catalogId);
        if (is_null($catalog)) {
            throw new \yii\web\HttpException(500, 'Good (ID:' . $catalogId . ') not found');
        }

        $sku = $catalog->getSkuList();
        $skuValues = Yii::$app->request->post('Sku');
        if ($sku->load(Yii::$app->request->post()) && $sku->validate()) {
            $sku->save($catalogId, $skuId, $skuValues['price'], $skuValues['acticle'], $skuValues['status']);
        } else {
            $result['success'] = false;
        }

        return $result;
    }

    public function actionSku_images()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;

        $skuId = Yii::$app->request->get('sku_id');
        $view = $this->getView();
        $view->clear();
        $view->beginPage();
        $view->head();
        $view->beginBody();

        echo MultiUpload::widget([
            'id' => 'sku',
            'pjaxId' => 'sku-pjax-images',
            'updateFancy' => Url::to(['catalogajax/sku_images', 'sku_id' => $skuId]),
            'modelName' => \app\models\CatalogSkuGallery::class,
            'relationValue' => $skuId,
            'relationField' => 'catalog_sku_id',
            'field' => 'image',
            'label' => 'Изображения',
            'type' => MultiUpload::TYPE_IMAGES,
            'fields' => [
                'name' => 'Изображение',
                'sort' => 0,
                'is_main' => ['type' => MultiUpload::FIELD_RADIO, 'value' => 0],
            ],
        ]);

        $view->endBody();
        $view->endPage(true);
    }

    public function actionSearch_goods()
    {
        $string = Yii::$app->request->get('q');
        $query = \app\models\Catalog::find();
        $query->andFilterWhere(['LIKE', 'name', $string]);
        $query->select(['id', 'catalog_categorie_id', 'name', 'image']);
        $query->orderBy(['name' => SORT_ASC]);
        $goods = $query->all();
        $currentId = Yii::$app->request->get('current', null);
        $currentGood = \app\models\Catalog::findOne($currentId);
        $disabledIds = [];
        if (!is_null($currentGood)) {
            $disabledIds = IcmsHelper::map($currentGood->relatedGoods, 'id');
        }
        $categoriesName = IcmsHelper::map(
                        \app\models\CatalogCategorie::find()->andWhere(['id' => IcmsHelper::map($goods, 'catalog_categorie_id')])->all(), 'id', 'name'
        );

        $result = [];

        $goodsByCategories = [];
        foreach ($goods as $good) {
            $goodsByCategories[$good->catalog_categorie_id][] = $good;
        }

        foreach ($goodsByCategories as $categorieId => $goods) {
            $group = [
                'text' => $categoriesName[$categorieId],
                'children' => []
            ];

            foreach ($goods as $good) {
                $image = $good->getResizePath('image', 25, 25);
                $group['children'][] = [
                    'id' => $good->id,
                    'text' => $good->name,
                    'image' => $image,
                    'disabled' => in_array($good->id, $disabledIds) || $good->id == $currentId
                ];
            }

            $result[] = $group;
        }

        return ['items' => $result];
    }

    public function actionAdd_related_good()
    {
        $goodId = Yii::$app->request->post('goodId');
        $relatedId = Yii::$app->request->post('relatedId');

        $good = \app\models\Catalog::findOne($goodId);
        if (is_null($good)) {
            throw new \yii\web\HttpException("Good (ID:{$goodId}) not found");
        }
        $relatedGood = \app\models\Catalog::findOne($relatedId);
        if (is_null($relatedGood)) {
            throw new \yii\web\HttpException("Good (ID:{$relatedId}) not found");
        }

        $good->link('relatedGoods', $relatedGood);

        return ['success' => true];
    }

    public function actionDelete_related_good()
    {
        $goodId = Yii::$app->request->post('goodId');
        $relatedId = Yii::$app->request->post('relatedId');

        $good = \app\models\Catalog::findOne($goodId);
        if (is_null($good)) {
            throw new \yii\web\HttpException("Good (ID:{$goodId}) not found");
        }
        $relatedGood = \app\models\Catalog::findOne($relatedId);
        if (is_null($relatedGood)) {
            throw new \yii\web\HttpException("Good (ID:{$relatedId}) not found");
        }

        $good->unlink('relatedGoods', $relatedGood);

        return ['success' => true];
    }

}
