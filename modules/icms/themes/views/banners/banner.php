<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\FileFlashInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\models\BannerGroup;
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
                <?= $form->field($model, 'name')->textInput()->label('Название') ?>
                <?= $form->field($model, 'link')->textInput()->label('Ссылка') ?>
                <div class="clear"></div>
                <div class='col-70'>
                    <div class="action_buttons">
                        <a class="back">Назад</a>
                        <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'type')->widget(DropDownList::class, ['items' => $model::getTypes()])->label('Тип') ?>
                <?= $form->field($model, 'sort')->textInput()->label('Сортировка') ?>
                <?php if ($model->isNewRecord || $model->type == $model::TYPE_IMAGE) { 
                    echo $form->field($model, 'file')->widget(FileImageInput::class)->label('Файл баннера');
                } else {
                    echo $form->field($model, 'file')->widget(FileFlashInput::class)->label('Файл баннера');
                    
                    echo $form->field($model, 'width')->textInput()->label('Ширина');
                    echo $form->field($model, 'height')->textInput()->label('Высота');
                }
                ?>
                <?= $form->field($model, 'banner_group_id')->widget(DropDownList::class, ['items' => BannerGroup::getNamesAsArray()])->label('Группа') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses()])->label('Статус') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <?php ActiveFormIcms::end(); ?>
</div>