<?php

use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\CheckBox;
use app\modules\icms\widgets\AliasInput;
?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin();
    ?>
    <?php
    $tabs = Tabs::begin([
                'tabNames' => [
                    'Общая информация',
                    count($propInfos) > 0 ? 'Свойства' : false,
                    'SEO',
                ]
    ]);
    ?>

    <?php $tabs->beginTab() ?>
    <div class='col-70'>
        <?=
        $form->field($model, 'pid')->widget(DropDownList::class, [
            'items' => $model::getNamedTreeAsArray(true),
            'options' => ['options' => $model::getDisabledBranch($model->id)]
        ])->label('Раздел')
        ?>
        <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
        <?=
        $form->field($model, 'alias')->widget(AliasInput::class, [
            'from' => \yii\helpers\Html::getInputId($model, 'name'),
            'autoEnabledField' => 'auto_url'
        ])->label('URL');
        ?>
        <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Описание') ?>
    </div>
    <div class="col-25 float_r">
        <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
        <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
        <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>

    <?php
    $this->registerJs(<<<JS
$(document).on('click','.propCategorieChange',function(){
    var cb=$(this);
    var propId=cb.attr('data-id');
    var propChecked=cb.prop('checked');
    var categoryId=$('#categoryId').val();
    $.post('/icms/catalogajax/prop_category_multy',{'propId':propId,'propChecked':propChecked,'categoryId':categoryId},
        function(data){
            $.gritter.add({'title':'Внимание','text':'Изменения сохранены!'});
        },'json');
});
JS
    )
    ?>

    <div class="col-70 float_l">
        <?php foreach ($propInfos as $key => $propInfo) { ?>
            <fieldset class="line-box">
                <legend><?= $propInfo['groupName'] ?></legend>
                <?php
                foreach ($propInfo['props'] as $prop) {
                    echo CheckBox::widget([
                        'name' => 'prop_' . $prop->id,
                        'checked' => in_array($prop->id, $catalogCategorieProps),
                        'choiceLabel' => $prop->name,
                        'options' => [
                            'class' => 'propCategorieChange',
                            'data-id' => $prop->id
                        ]
                    ]);
                }
                ?>
            </fieldset>
        <?php } ?>
    </div>

    <?php if (Yii::$app->user->can('developer') === true) { ?>
        <div class="col-25 float_r">
            <?= app\modules\icms\widgets\sku\SkuCatalogGeneratorModal::widget(['categorieId' => $model->id]) ?>
        </div>
    <?php } ?>
    <?php $tabs->endTab() ?>
    <?php $tabs->beginTab() ?>
    <div class='col-100'>
        <?= $form->field($model, 'title_seo')->textInput(['class' => 'width-100'])->label('Title') ?>
        <?= $form->field($model, 'description_seo')->textarea(['class' => 'width-100'])->label('Description') ?>
        <?= $form->field($model, 'keywords_seo')->textarea(['class' => 'width-100'])->label('Keywords') ?>
    </div>
    <div class="col-100">
        <fieldset class="line-box">
            <legend>Описание шорткодов для генерации</legend>
            {{название категории}} - название категории<br>
            {{название товара}} - название товара<br>
            {{цена}} - цена товара<br>
            {{артикул}} - артикул товара<br>
        </fieldset>
    </div>
    <div class="col-49">
        <fieldset class="line-box">
            <legend>Генерация SEO для категорий</legend>
            <?= CheckBox::widget(['name' => 'generator[categories][start]', 'choiceLabel' => 'Генерировать']) ?>
            <?= CheckBox::widget(['name' => 'generator[categories][override]', 'choiceLabel' => 'Переписывать существующие']) ?>
            <fieldset>
                <label>Title</label>
                <?= Html::textInput('generator[categories][title]', null, ['class' => 'width-100']) ?>
            </fieldset>
            <fieldset>
                <label>Description</label>
                <?= Html::textarea('generator[categories][description]', null, ['class' => 'width-100']) ?>
            </fieldset>
            <fieldset>
                <label>Keywords</label>
                <?= Html::textarea('generator[categories][keywords]', null, ['class' => 'width-100']) ?>
            </fieldset>
        </fieldset>
    </div>
    <div class="col-49 float_r">
        <fieldset class="line-box">
            <legend>Генерация SEO для товаров внутри категории "<?= $model->name ?>"</legend>
            <?= CheckBox::widget(['name' => 'generator[catalog][start]', 'choiceLabel' => 'Генерировать']) ?>
            <?= CheckBox::widget(['name' => 'generator[catalog][override]', 'choiceLabel' => 'Переписывать существующие']) ?>
            <fieldset>
                <label>Title</label>
                <?= Html::textInput('generator[catalog][title]', null, ['class' => 'width-100']) ?>
            </fieldset>
            <fieldset>
                <label>Description</label>
                <?= Html::textarea('generator[catalog][description]', null, ['class' => 'width-100']) ?>
            </fieldset>
            <fieldset>
                <label>Keywords</label>
                <?= Html::textarea('generator[catalog][keywords]', null, ['class' => 'width-100']) ?>
            </fieldset>
        </fieldset>
    </div>
    <?php $tabs->endTab() ?>
    <?php $tabs::end() ?>
    <div class="clear"></div>
    <input type="hidden" id="categoryId" value="<?= $model->id ?>">
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end() ?>
</div>
