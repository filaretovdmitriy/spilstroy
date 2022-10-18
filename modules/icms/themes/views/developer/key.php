<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\models\Key;
?>
<div class="data">
    <?php $form = ActiveFormIcms::begin() ?>
    <?php $tabs = Tabs::begin([
            'tabNames' => ['Общая информация']
    ]) ?>
        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'name')->textInput()->label('Название') ?>
                <?= $form->field($model, 'value')->textarea(['class' => 'width-100'])->label('Значение') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'pid')->widget(DropDownList::class, ['items' => [0 => 'Нет'] + Key::getAsArray()])->label('Группа') ?>
            </div>
        <?php $tabs->endTab() ?>
    <?php $tabs::end() ?>
    <div class="clear"></div>
    <div class='col-100'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end() ?>
</div>