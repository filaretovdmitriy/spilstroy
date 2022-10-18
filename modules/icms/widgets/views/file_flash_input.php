<?php

use yii\helpers\Html;

if ($imageName) {
    ?>
    <div class="image">
        <span class="img_view">
            <embed
                src="<?= $imagePreview ?>" width="170" height="150"
                type="application/x-shockwave-flash"
                pluginspage="http://www.macromedia.com/go/getflashplayer"
                >
            <a class="del_pic button" id="<?= get_class($model) ?>-<?= $attribute ?>-<?= $model->id ?>">Удалить файл</a>
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