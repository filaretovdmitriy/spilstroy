<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\models\GalleryCategorie;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
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
                <?= $form->field($model, 'gallery_categorie_id')->widget(DropDownList::class, ['items' => GalleryCategorie::getNamedTreeAsArray(), 'placeholder' => 'Выберите раздел'])->label('Раздел') ?>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>


            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
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
