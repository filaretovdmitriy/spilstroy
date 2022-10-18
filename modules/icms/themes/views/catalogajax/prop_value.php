<?php
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\FileImageInput;
use yii\helpers\Html;
?>

<div class="prop-value-modal data content">
    <?php if ($model->isNewRecord) { ?>
        <h2>Создание нового значения свойства</h2>
    <?php } else { ?>
        <h2>Редактирование свойства</h2>
    <?php } ?>
        
        <?php $form = ActiveFormIcms::begin(['id' => 'prop-value-modal-form']) ?>
        <div class="col-60">
            <?= $form->field($model, 'name')->textInput()->label('Значение') ?>
            <?= $form->field($model, 'sort')->textInput()->label('Сортировка') ?>
            <?= $form->field($model, 'props_id')->hiddenInput()->label(false) ?>
            <?php if (!$model->isNewRecord) { ?>
                <?= Html::hiddenInput('value_id', $model->id) ?>
            <?php } ?>
        </div>
        <div class="col-35 float_r">
            <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
        </div>
        <div class="col-100">
            <div class="action_buttons">
                <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
            </div>
        </div>
        <?php $form->end();?>
</div>