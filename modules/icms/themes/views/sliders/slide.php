<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\models\Slider;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\ckeditor\CKEditor;
?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin();
    ?>
        <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация']
        ]);
        ?>

        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'name')->textInput()->label('Название слайда') ?>
                <?= $form->field($model, 'link')->textInput()->label('Ссылка') ?>
                <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Текст слайда') ?>
                <div class="clear"></div>
                <div class='col-70'>
                    <div class="action_buttons">
                        <a class="back">Назад</a>
                        <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'sort')->textInput()->label('Сортировка') ?>
                <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
                <?= $form->field($model, 'slider_id')->widget(DropDownList::class, ['items' => Slider::getNamesAsArray(), 'placeholder' => 'Выберите слайдер'])->label('Слайдер') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <?php ActiveFormIcms::end(); ?>
</div>