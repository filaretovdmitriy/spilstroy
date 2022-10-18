<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\AliasInput;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\sku;
use app\modules\icms\widgets\CheckBoxSlide;
use app\modules\icms\widgets\multi_upload\MultiUpload;
?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin();
    ?>
    <?php
    $tabs = Tabs::begin([
        'tabNames' => [
            'Общая информация',
            count($model->propsCode->attributes) > 0?'Свойства':false,
            count($model->getProperties(true)) > 0?'Торговые предложения':false,
            'Сопутствующие товары',
            'Отзывы',
            'SEO'
        ]
    ]);
    ?>

    <?php $tabs->beginTab() ?>
    <div class='col-70'>
        <?= $form->field($model, 'catalog_categorie_id')->widget(DropDownList::class, ['items' => \app\models\CatalogCategorie::getNamedTreeAsArray(true), 'placeholder' => 'Выберите раздел'])->label('Раздел') ?>
        <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
        <?= $form->field($model, 'alias')->widget(AliasInput::class, [
            'from' => \yii\helpers\Html::getInputId($model, 'name'),
            'autoEnabledField' => 'auto_url'
        ])->label('URL') ?>
        <?= $form->field($model, 'article')->textInput(['class' => 'width-100'])->label('Артикул') ?>
        <?= $form->field($model, 'price')->textInput(['class' => 'width-100'])->label('Цена') ?>
        <?= $form->field($model, 'moscowprice')->textInput(['class' => 'width-100'])->label('Цена со склада') ?>
        <?= $form->field($model, 'price_old')->textInput(['class' => 'width-100'])->label('Цена "старая"') ?>
        <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Описание') ?>
    </div>
    <div class="col-25 float_r">
        <?= $form->field($model, 'is_popular')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет / Да'])->label("Популярный товар?") ?>
        <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
        <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
        <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
        <?= MultiUpload::widget([
            'modelName' => app\models\CatalogGallery::class,
            'relationValue' => $model->id,
            'relationField' => 'catalog_id',
            'field' => 'image',
            'label' => 'Изображения',
            'type' => MultiUpload::TYPE_IMAGES,
            'fields' => [
                'name' => 'Изображение',
                'sort' => 0,
            ],
        ]) ?>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>
    <div class='col-70'>
        <?php
        $htmlProps = [];
        foreach ($model->propsCode as $key => $prop) {
            $html = "<fieldset class='clearfix'><div class='form-group'>";
            $html .= \app\components\IcmsHelper::renderProp($model->propsCode, $key, $form, $allPropsArray);
            $html .= "</div></fieldset>";
            $htmlProps[$allPropsArray[$key]['props_groups_id']][] = $html;
        }
        foreach ($htmlProps as $groupId => $propsHtml) { ?>
            <fieldset class="line-box">
                <legend><?= $propGroups[$groupId] ?></legend>
                <?php foreach ($propsHtml as $propHtml) {
                    echo $propHtml;
                } ?>
            </fieldset>
        <?php } ?>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>
    <div class="col-100">
        <?= sku\SkuModal::widget(['catalogId' => $model->id]) ?>
        <?= sku\SkuGeneratorModal::widget(['catalogId' => $model->id]) ?>

        <?php $this->registerJs(<<<JS
$(document).on('click', '#catalog-sku-delete', function() {
    catalogId = $(this).data('catalog-id');
    if (!confirm('Вы действительно хотите удалить все торговые предложения этого товара?')) {
        return false;
    }
    $.post('/icms/catalogajax/delete_sku_all', {catalogId: catalogId}, function(data) {
        if (data.success) {
            $.pjax.reload('#' + $('.pjax-sku-wraper').attr('id'));
            $.gritter.add({'title':'Удалено','text': 'Торговые предложения удалены'});
        }
    }, 'json');

    return false;
});
JS
        ) ?>

        <button type="button" class="button float_r" id="catalog-sku-delete" data-catalog-id="<?= $model->id ?>">Удалить все торговые предложения</button>
        <?= sku\SkuTable::widget(['skuList' => $model->skus, 'allCatalogSku' => $model->getProperties(true), 'catalogModel' => $model]) ?>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>
    
    <div class="col100">
        <div>
            <?= app\modules\icms\widgets\RelatedGoodTab::widget(['model' => $model]) ?>
        </div>
    </div>
    
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>
    <div class="col-100">
        <?php app\components\Pjax::begin(['id' => 'feed-backs-list', 'options' => ['class' => 'pjax-wraper']]) ?>
        <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
        <?= \app\modules\icms\widgets\grid\GridView::widget([
            'modelName' => app\models\Feedback::class,
            'filterScenario' => 'filter',
            'conditions' => ['catalog_id' => $model->id],
            'options' => ['class' => 'table-tab'],
            'filter' => false,
            'columns' => [
                'id',
                [
                    'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                    'attribute' => 'name',
                    'label' => 'ФИО',
                    'format' => 'link',
                    'options' => [
                        'link' => ['feedbacks/edit', 'id']
                    ]
                ],
                [
                    'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                    'attribute' => 'content',
                    'label' => 'Текст',
                    'format' => ['cut', 'nl2br'],
                    'options' => [
                        'length' => 50
                    ]
                ],
                [
                    'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                    'attribute' => 'created_date',
                    'label' => 'Дата создания (для отображения)',
                    'format' => 'date'
                ],
                [
                    'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                    'attribute' => 'created_at',
                    'label' => 'Дата создания',
                    'format' => 'date'
                ],
                [
                    'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'contentOptions' => ['class' => 'width-200'],
                    'format' => 'select',
                    'options' => [
                        'items' => app\models\Feedback::getStatuses()
                    ]
                ],
                [
                    'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                    'delete' => true,
                    'save' => true,
                    'view' => ['feedbacks/edit', 'id'],
                ],
            ],
        ]) ?>
        <?php app\components\Pjax::end() ?>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>
    <div class='col-70'>
        <?= $form->field($model, 'title_seo')->textInput(['class' => 'width-100'])->label("Title") ?>
        <?= $form->field($model, 'description_seo')->textarea(['class' => 'width-100'])->label('Description') ?>
        <?= $form->field($model, 'keywords_seo')->textarea(['class' => 'width-100'])->label('Keywords') ?>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs::end() ?>
    <div class="clear"></div>
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end(); ?>
</div>
