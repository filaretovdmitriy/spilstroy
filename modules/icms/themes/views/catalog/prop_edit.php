<?php
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\CheckBoxSlide;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\grid\GridView;
use app\modules\icms\widgets\fancy_box\FancyBox;
use yii\widgets\Pjax;
?>
<div class="data">
    <?php
    $tabs = Tabs::begin([
        'tabNames' => $model->type->is_multy?['Общая информация', 'Значения']:['Общая информация']
    ]);
    ?>

    <?php $tabs->beginTab() ?>
    <?php $form = ActiveFormIcms::begin() ?>
    <div class='col-70'>
        <h2><?= $model->type->name ?> - Код: <?= $model->alias ?></h2>
        <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
        <?php if ($model->prop_type_id == 4 && Yii::$app->user->can('developer')){
            echo $form->field($model, 'prop_type_list_id')->widget(DropDownList::class, ['items' => \app\models\PropTypeList::getNamesAsArray(), 'placeholder' => 'Выберите тип списка'])->label('Тип списка');
        } ?>


    </div>
    <div class="col-25 float_r">
        <?php if(Yii::$app->user->can('developer')) { ?>
            <?= $form->field($model, 'props_groups_id')->widget(DropDownList::class, ['items' => [0 => 'Нет'] + \app\models\PropsGroup::getNamesAsArray()])->label('Группа') ?>
            <?= $form->field($model, 'is_sku')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Свойство SKU") ?>
            <?= $form->field($model, 'is_filter')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Отображать в фильтре") ?>
            <?= $form->field($model, 'is_most')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Обязательное поле") ?>
        <?php } ?>
        <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
    </div>
    <div class="clear"></div>
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= \yii\helpers\Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end() ?>
    <?php $tabs->endTab() ?>
    <?php if ($model->type->is_multy == 1) { ?>
        <?php $tabs->beginTab() ?>
        <div class="col-100">
            <fieldset>
                <?= FancyBox::widget([
                    'target' => '.prop-value-edit',
                    'config' => [
                        'type' => 'ajax'
                    ]]) ?>
                <?= FancyBox::widget([
                    'target' => '#prop-add-value',
                    'config' => [
                        'type' => 'ajax'
                    ]
                ]) ?>
                <a id="prop-add-value" class="button" href="<?= \yii\helpers\Url::to(['catalogajax/edit_value', 'prop_id' => $model->id]) ?>">Добавить новое значение</a>
            </fieldset>
            <?php Pjax::begin(['id' => 'props-values', 'options' => ['class' => 'pjax-wraper']]) ?>
            <?= GridView::widget([
                'modelName' => app\models\PropsValue::class,
                'tableName' => 'Значения свойства',
                'filterScenario' => 'filter',
                'conditions' => ['props_id' => $model->id],
                'options' => ['class' => 'table-prop-values'],
                'columns' => [
                    'id',
                    [
                        'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                        'attribute' => 'name',
                        'label' => 'Значение',
                        'format' => 'link',
                        'options' => [
                            'link' => ['catalogajax/edit_value', 'prop_id' => 'props_id', 'value_id' => 'id'],
                            'htmlOptions' => ['class' => 'prop-value-edit']
                        ]
                    ],
                    [
                        'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                        'attribute' => 'image',
                        'label' => 'Изображение',
                        'format' => 'img',
                        'options'=>[
                            'resize'=>['height' => 40, 'type' => 4]
                        ]
                    ],
                    [
                        'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                        'attribute' => 'sort',
                        'label' => 'Сортировка',
                        'contentOptions' => ['class' => 'width-120'],
                        'format' => 'input'
                    ],
                    [
                        'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                        'delete' => true,
                        'save' => true,
                    ],
                ]
            ]) ?>
            <?php Pjax::end() ?>
        </div>
        <div class="clearfix"></div>
        <?php $tabs->endTab() ?>
    <?php } ?>
    <?php $tabs::end() ?>
</div>
