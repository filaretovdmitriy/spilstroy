<?php
use app\modules\icms\widgets\ActiveFormIcms;
use app\models\CatalogCategorie;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\AliasInput;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\CheckBoxSlide;
?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin();
    ?>
    <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация', 'SEO']
        ]);
        ?>

        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'catalog_categorie_id')->widget(DropDownList::class, ['items' => CatalogCategorie::getNamedTreeAsArray(true), 'placeholder' => 'Выберите раздел'])->label('Раздел') ?>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
                <?= $form->field($model, 'alias')->widget(AliasInput::class, [
                    'from' => \yii\helpers\Html::getInputId($model, 'name'),
                    'autoEnabledField' => 'auto_url',
                    'autoEnabled' => true
                ])->label('URL'); ?>
                <?= $form->field($model, 'article')->textInput(['class' => 'width-100'])->label('Артикул') ?>
                <?= $form->field($model, 'price')->textInput(['class' => 'width-100'])->label('Цена') ?>
                <?= $form->field($model, 'price_old')->textInput(['class' => 'width-100'])->label('Цена "старая"') ?>
                <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Описание') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'is_popular')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет / Да'])->label("Популярный товар?") ?>
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100', 'value' => '0'])->label('Сортировка') ?>
                <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'title_seo')->textInput(['class' => 'width-100'])->label("Title") ?>
                <?= $form->field($model, 'description_seo')->textarea(['class' => 'width-100'])->label('Description')
                ?>
                <?= $form->field($model, 'keywords_seo')->textarea(['class' => 'width-100'])->label('Keywords')
                ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <div class="clear"></div>
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= \yii\helpers\Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end(); ?>
</div>
