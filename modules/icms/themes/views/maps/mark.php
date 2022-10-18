<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\yandex_map\YandexMapImageInput;
use app\models\Map;
use app\modules\icms\widgets\yandex_map\YandexMapColorList;
use app\modules\icms\widgets\yandex_map\YandexMapEdit;
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
                <?php if (!empty($model->map_id)) { ?>
                    <?= Html::label('Перетащите крастную метку в нужно место') ?>
                    <?= YandexMapEdit::widget([
                        'map' => $model->map,
                        'mark' => $model,
                        'marks' => !is_null($model->map)?$model->map->marks:[],
                        'type' => YandexMapEdit::TYPE_MARK,
                    ]) ?>
                <?php } else { ?>
                    <div class="notice">Сохраните метку для выбора её расположения на карте</div>
                <?php } ?>
                <?= $form->field($model, 'content')->textarea()->label('Текст') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
                <?= $form->field($model, 'map_id')->widget(DropDownList::class, ['items' => Map::getNamesAsArray(), 'placeholder' => 'Выберите карту'])->label('Карта') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
                <?= $form->field($model, 'image')->widget(YandexMapImageInput::class)->label('Изображение' . (!empty($model->image)?' (Выберите острие)':'')) ?>
                <?= $form->field($model, 'color')->widget(YandexMapColorList::class)->label('Цвет') ?>
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
