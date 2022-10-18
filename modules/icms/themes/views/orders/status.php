<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\CheckBoxSlide;
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
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'can_cancel')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label('Можно отменить?') ?>
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
    <?php $form->end() ?>
</div>
