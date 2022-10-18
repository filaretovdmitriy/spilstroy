<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\AliasInput;
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
                <?= $form->field($model, 'pid')->widget(DropDownList::class, ['items' => $model::getNamedTreeAsArray(true), 'placeholder' => 'Выберите раздел'])->label('Раздел') ?>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
                <?= $form->field($model, 'alias')->widget(AliasInput::class, [
                    'from' => \yii\helpers\Html::getInputId($model, 'name'),
                    'autoEnabledField' => 'auto_url',
                    'autoEnabled' => true
                ])->label('URL'); ?>
                <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Описание') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
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
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end() ?>
</div>
