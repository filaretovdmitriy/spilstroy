<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\FileInput;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\CheckBoxSlide;
use app\components\Pjax;
?>
<div class="data">
    <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация', $model->isNewRecord === false && $model->type == $model::TYPE_MULTI?'Значения':false]
        ]);
        ?>

        <?php $form = ActiveFormIcms::begin() ?>
        <?php $tabs->beginTab(true) ?>
            <div class='col-70'>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
                <?php if ($model->isNewRecord === false) {
                    switch ($model->type) {
                        case $model::TYPE_TEXT:
                            echo $form->field($model, 'value')->textInput(['class' => 'width-100'])->label('Значение');
                            break;
                        case $model::TYPE_HTML:
                            echo $form->field($model, 'value')->widget(CKEditor::class)->label('Значение');
                            break;
                        case $model::TYPE_BOOLEAN:
                            echo $form->field($model, 'value')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label('Включено?');
                            break;
                    }
                } ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'type')->widget(DropDownList::class, [
                    'items' => $model::getTypes(),
                    'placeholder' => 'Выберите тип',
                    'options' => [
                        'disabled' => Yii::$app->user->can('developer') === false && $model->isNewRecord === false,
                    ]
                ])->label('Тип параметра') ?>
                <?php
                if ($model->type == $model::TYPE_IMAGE && $model->isNewRecord === false) {
                    echo $form->field($model, 'value')->widget(FileImageInput::class)->label('Изображение');
                }
                if ($model->type == $model::TYPE_FILE && $model->isNewRecord === false) {
                    echo $form->field($model, 'value')->widget(FileInput::class)->label('Файл');
                }
                ?>
            </div>
            <div class="clear"></div>
            <div class='col-100'>
                <div class="action_buttons">
                    <a class="back">Назад</a>
                    <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                </div>
            </div>
        <?php $tabs->endTab() ?>
        <?php $form->end() ?>
        <?php $tabs->beginTab() ?>
            <div class="col-100">

                <?php $form = ActiveFormIcms::begin([
                    'action' => '/icms/parameters/value_add',
                    'options' => ['class' => 'js-add-parameter-value']
                ]) ?>

                <h2>Добавить новое значение</h2>
                <?= $form->field($value, 'parameter_id')->hiddenInput()->label(false) ?>
                <div class="col-41 float_l">
                    <?= $form->field($value, 'value')->textInput()->label('Значение') ?>
                </div>
                <div class="col-42 float_l margin-left-15">
                    <?= $form->field($value, 'sort')->textInput()->label('Сортировка') ?>
                </div>
                <div class="col-15 float_r">
                    <br>
                    <button type="submit" class="button">Добавить</button>
                </div>

                <?php $form->end() ?>

                <div class="clear"></div>

                <?php Pjax::begin([
                    'id' => 'parameters-values-pjax',
                    'linkSelector' => 'a[data-is-pjax]',
                    'options' => ['class' => 'pjax-wraper']
                ]) ?>

                <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
                <?= app\modules\icms\widgets\grid\GridView::widget([
                    'modelName' => 'app\models\ParameterValue',
                    'filterScenario' => 'filter',
                    'tableName' => 'Значения',
                    'options' => ['class' => 'table-prop-values'],
                    'conditions' => ['parameter_id' => $model->id],
                    'columns' => [
                        'id',
                        [
                            'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                            'attribute' => 'value',
                            'label' => 'Значение',
                            'format' => 'input',
                        ],
                        [
                            'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                            'attribute' => 'sort',
                            'label' => 'Сортировка',
                            'format' => 'input',
                        ],
                        [
                            'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                            'attribute' => 'created_at',
                            'label' => 'Дата создания',
                            'visible' => Yii::$app->user->can('developer'),
                            'format' => 'date'
                        ],
                        [
                            'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                            'attribute' => 'updated_at',
                            'label' => 'Дата изменения',
                            'visible' => Yii::$app->user->can('developer'),
                            'format' => 'date'
                        ],
                        [
                            'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                            'delete' => true,
                            'save' => true
                        ],
                    ],
                ]); ?>

                <?php Pjax::end() ?>

                <?php $this->registerJs(<<<JS
$('.js-add-parameter-value').on('beforeSubmit', function() {
    var elem = $(this);
    if (elem.find('.{$form->errorCssClass}').length < 1) {

        $.post(elem.attr('action'), elem.serialize(), function(data) {
            if (data.success) {
                elem.find('input[type="text"]').val('');
                elem.yiiActiveForm('resetForm');
                $.pjax.reload('#parameters-values-pjax');
                $.gritter.add({title: 'Сохранение', text: 'Значение добавлено'});
            } else {
                $.gritter.add({title: 'Ошибка', text: 'Не удалось добавить новое значение'});
            }
        }, 'json');
    }
    return false;
});
JS
                ) ?>

            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
</div>
