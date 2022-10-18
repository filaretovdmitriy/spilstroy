<?php
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
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
                <?= $form->field($model, 'prop_type_id')->widget(DropDownList::class, ['items' => \app\models\PropType::getNamesAsArray(), 'placeholder' => 'Выберите тип'])->label('Тип') ?>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
                <?= $form->field($model, 'alias')->textInput(['class' => 'width-100'])->label('Символьный код (только латинсике буквы и цифры)') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'props_groups_id')->widget(DropDownList::class, ['items' => [0 => 'Нет'] + \app\models\PropsGroup::getNamesAsArray()])->label('Группа') ?>
                <?= $form->field($model, 'is_sku')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Свойство SKU") ?>
                <?= $form->field($model, 'is_filter')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Отображать в фильтре") ?>
                <?= $form->field($model, 'is_most')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Обязательное поле") ?>
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100', 'value' => '100'])->label('Сортировка') ?>
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
