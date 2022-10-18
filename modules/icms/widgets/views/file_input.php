<?php

use yii\helpers\Html;
use app\components\IcmsHelper;
?>
<?php if (!empty($fileName)) { ?>
    <div class="image">
        <span class="img_view">
            <?= Html::a('Скачать', $filePath, ['class' => 'button', 'download' => true]) ?>
            <a class="del_file button" id="<?= get_class($model) ?>-<?= $attribute ?>-<?= $model->id ?>">Удалить</a>
        </span>
    </div>
<?php } ?>
<div class="img_load" <?= !empty($fileName) ? 'style="display: none;"' : '' ?>>
    <label class="file" for="<?= $hasModel ? Html::getInputId($model, $attribute) : IcmsHelper::clearString($name) ?>">
        <div class="val"></div>
        <?= $hasModel ? Html::activeFileInput($model, $attribute, ['style' => 'display:none']) : Html::fileInput($name, null, ['id' => IcmsHelper::clearString($name), 'style' => 'display:none']) ?>
        <div class="input">Обзор</div>
    </label>
    <?php if ($maxFileSize > 0) { ?>
        <small>Размер не более <strong><?= str_replace('.00', '', IcmsHelper::getSymbolByQuantity($maxFileSize)) ?></strong></small>
    <?php } ?>
    <div class="clear"></div>
</div>
