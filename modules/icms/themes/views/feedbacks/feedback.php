<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\date_time_input\DateTimeInput;
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
                <?= $form->field($model, 'name')->textInput()->label('Имя') ?>
                <?= $form->field($model, 'content')->textarea()->label('Текст') ?>
            </div>
            <div class='col-25 float_r'>
                <?= $form->field($model, 'created_date')->widget(DateTimeInput::class)->label('Дата') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
            </div>
        <?php $tabs->endTab() ?>
    <?php Tabs::end(); ?>
    <div class="clear"></div>
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end(); ?>
</div>

