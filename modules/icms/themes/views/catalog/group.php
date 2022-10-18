<?php
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\Tabs;

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
        <?php if (!$model->isNewRecord) { ?>
            <h2>Код: <?= $model->alias ?></h2>
        <?php } ?>
        <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
        <?php if ($model->isNewRecord) { ?>
            <?= $form->field($model, 'alias')->textInput(['class' => 'width-100'])->label('Алиас') ?>
        <?php } ?>
    </div>
    <div class="col-25 float_r">
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
    <?php ActiveFormIcms::end() ?>
</div>
