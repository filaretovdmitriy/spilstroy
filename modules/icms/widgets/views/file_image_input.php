<?php

use app\components\IcmsHelper;
use yii\helpers\Html;
use app\modules\icms\widgets\CheckBox;
?>
<?php if ($imageName) { ?>
    <div class="image">
        <span class="img_view">
            <a href="<?= $model->getPath($attribute) ?>" target="_blank">
                <img src="<?= app\components\IcmsHelper::getResizePath($imagePreview, 170, 150, 2) ?>">
            </a>
            <a class="del_pic button" id="<?= get_class($model) ?>-<?= $attribute ?>-<?= $model->id ?>">Удалить изображение</a>
        </span>
    </div>
<?php } ?>
<div class="img_load <?= $resize ? 'image-crop line-box' : '' ?>" <?= $imageName ? 'style="display: none;"' : '' ?>>
    <label class="file" for="<?= Html::getInputId($model, $attribute) ?>">
        <div class="val"></div>
        <?= Html::activeFileInput($model, $attribute, ['style' => 'display:none']) ?>
        <div class="input">
            Обзор
        </div>
    </label>

    <?php if ($maxFileSize > 0) { ?>
        <small>Размер не более <strong><?= str_replace('.00', '', IcmsHelper::getSymbolByQuantity($maxFileSize)) ?></strong></small>
    <?php } ?>

    <?php if ($resize === true) { ?>
        <div class="cropper-block">
            <label>Изменение размера</label>
            <?= Html::textInput($model::CROP_REQUEST_NAME . "[" . get_class($model) . "][{$attribute}]" . '[width]', null, ['placeholder' => 'Ширина', 'class' => 'crop-width', 'disabled' => true]) ?>
            <?= Html::textInput($model::CROP_REQUEST_NAME . "[" . get_class($model) . "][{$attribute}]" . '[height]', null, ['placeholder' => 'Высота', 'class' => 'crop-height', 'disabled' => true]) ?>
            <?= CheckBox::widget(['name' => $model::CROP_REQUEST_NAME . "[" . get_class($model) . "][{$attribute}]" . '[check]', 'value' => '1', 'options' => ['class' => 'cropper']]) ?>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>
