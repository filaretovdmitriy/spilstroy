<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\multi_upload\MultiUpload;
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
                <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Текст') ?>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
                <?php if ($model->isNewRecord === false) { ?>
                    <?= MultiUpload::widget([
                        'modelName' => app\models\CatalogPayGallery::class,
                        'relationValue' => $model->id,
                        'relationField' => 'catalog_pay_id',
                        'field' => 'image',
                        'label' => 'Изображения',
                        'type' => MultiUpload::TYPE_IMAGES,
                        'fields' => [
                            'name' => 'Изображение',
                            'sort' => 0,
                        ],
                    ]) ?>
                <?php } ?>
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
