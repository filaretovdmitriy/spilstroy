<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
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
                <?= Html::label('Центрировка и настройка зума') ?>
                <?= YandexMapEdit::widget([
                    'map' => $model,
                    'marks' => $model->marks,
                    'type' => YandexMapEdit::TYPE_MAP
                ]) ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название карты') ?>
                <?= $form->field($model, 'mark_default_color')->widget(YandexMapColorList::class)->label('Цвет меток по умолчанию') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
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
    <?php ActiveFormIcms::end() ?>
</div>
