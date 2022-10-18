<?php

use yii\helpers\Html;
?>
<?php if ($imageName) { ?>
    <div class="image">
        <span class="img_view">
            <div id="containment-wrapper-ya" class="containment-wrapper-ya">
                <img src="<?= $imagePreview ?>">
                <span id="draggable-ya" class="draggable-ya-point" style="left: <?= $model->{$attributeX} - 5 ?>px; top: <?= $model->{$attributeY} - 5 ?>px"></span>
            </div>
            <?= Html::activeHiddenInput($model, $attributeX) ?>
            <?= Html::activeHiddenInput($model, $attributeY) ?>
            <a class="del_pic button" id="<?= get_class($model) ?>-<?= $attribute ?>-<?= $model->id ?>">Удалить изображение</a>
        </span>
    </div>
<?php } ?>
<div class="img_load" <?= $imageName ? 'style="display: none;"' : '' ?>>
    <label class="file" for="<?= Html::getInputId($model, $attribute) ?>">
        <div class="val"></div>
        <?= Html::activeFileInput($model, $attribute, ['style' => 'display:none']) ?>
        <div class="input">Обзор</div>
    </label>
    <div class="clear"></div>
</div>