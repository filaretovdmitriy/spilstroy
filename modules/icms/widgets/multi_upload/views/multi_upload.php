<?php
/* @var $this \yii\web\View */
/* @var $file app\components\db\ActiveRecordFiles */
/* @var $resize bool */
/* @var $type int */
/* @var $relationField string */
/* @var $relationValue string */
/* @var $fields array */
/* @var $label string */
/* @var $id string */
/* @var $fileField string */
/* @var $fileField string */
/* @var $maxFileSize integer */

use app\components\IcmsHelper;
use yii\helpers\Html;
use app\components\db\ActiveRecordFiles;
use app\modules\icms\widgets\CheckBox;
use app\modules\icms\widgets\Radio;
use yii\widgets\Pjax;
use app\modules\icms\widgets\multi_upload\MultiUpload;
?>

<fieldset id="<?= $id ?>" class="multi-uploader">
    <label><?= $label ?></label>
    <div class="line-box">
        <div id="fake-upload-form-<?= $id ?>">

            <?= Html::hiddenInput('model', get_class($file)) ?>
            <?= Html::activeHiddenInput($file, $relationField, ['value' => $relationValue]) ?>
            <?php foreach ($fields as $field => $info) { ?>
                <?php if (is_array($info) === true) { ?>
                    <?= Html::activeHiddenInput($file, $field, ['value' => isset($info['value']) === true ? $info['value'] : '']) ?>
                <?php } else { ?>
                    <?= Html::activeHiddenInput($file, $field, ['value' => $info]) ?>
                <?php } ?>
            <?php } ?>

            <fieldset class="uploader-wrapper" id="multi-upload-input-block-<?= $id ?>">
                <div class="progress"><div class="bar" style="width: 0%;"></div></div>
                <label class="upload-button" for="multi-upload-input-<?= $id ?>">
                    Обзор
                    <?=
                    Html::activeFileInput($file, $fileField, [
                        'class' => 'btn',
                        'multiple' => true,
                        'hidden' => true,
                        'id' => 'multi-upload-input-' . $id,
                    ])
                    ?>
                </label>
            </fieldset>

            <?php if ($maxFileSize > 0) { ?>
                <small>Размер одного файла должен быть не более <strong><?= str_replace('.00', '', IcmsHelper::getSymbolByQuantity($maxFileSize)) ?></strong></small>
            <?php } ?>

            <?php if ($resize === true) { ?>
                <fieldset class="image-crop cropper-block">
                    <label>Изменение размера</label>
                    <?= Html::textInput(ActiveRecordFiles::CROP_REQUEST_NAME . '[' . get_class($file) . '][image][width]', null, ['placeholder' => 'Ширина', 'class' => 'crop-width', 'disabled' => true]) ?>
                    <?= Html::textInput(ActiveRecordFiles::CROP_REQUEST_NAME . '[' . get_class($file) . '][image][height]', null, ['placeholder' => 'Высота', 'class' => 'crop-height', 'disabled' => true]) ?>
                    <?= CheckBox::widget(['name' => ActiveRecordFiles::CROP_REQUEST_NAME . '[' . get_class($file) . '][image][check]', 'value' => '1', 'options' => ['class' => 'cropper']]) ?>
                </fieldset>
            <?php } ?>

        </div>

        <div class="error-block" style="display: none"></div>

        <?php
        Pjax::begin([
            'id' => $pjaxId,
            'options' => ['class' => 'pjax-images'],
        ])
        ?>

        <?php
        foreach ($file::find()->andWhere([$relationField => $relationValue])->orderBy(['sort' => SORT_ASC])->all() as $showFile) {
            $options = [
                'data-id' => $showFile->id,
                'data-model' => get_class($file),
            ];
            ?>
            <div class="uppic-container">
                <?php if ($type === MultiUpload::TYPE_IMAGES) { ?>
                    <a href="<?= $showFile->getPath($fileField) ?>" data-pjax="0" target="_blank" class="upload-image">
                        <img src="<?= $showFile->getResizePath($fileField, 170, 150, 2) ?>">
                    </a>
                <?php } else { ?>
                    <div class="upload-file">
                        <span class="format">
                            <?= preg_replace('/(^.*\.)/', '', $showFile->{$fileField}) ?>
                        </span>
                        <a href="<?= $showFile->getPath($fileField) ?>" data-pjax="0" download traget="_blank" class="button">Скачать</a>
                    </div>
                <?php } ?>

                <div class="js-file-inputs">
                    <?php foreach ($fields as $field => $info) { ?>
                        <fieldset>
                            <?php if (is_array($info) === true && empty($info['type']) === false) { ?>
                                <?php if ($info['type'] === MultiUpload::FIELD_CHECKBOX) { ?>
                                    <?=
                                    CheckBox::widget([
                                        'model' => $showFile,
                                        'attribute' => $field,
                                        'choiceLabel' => $showFile->getAttributeLabel($field),
                                        'options' => ['class' => 'upload-image-input'] + $options,
                                    ])
                                    ?>
                                <?php } elseif ($info['type'] === MultiUpload::FIELD_TEXTAREA) { ?>
                                    <?=
                                    Html::activeTextarea($showFile, $field, [
                                            'class' => 'width-100 upload-image-input'
                                        ] + $options)
                                    ?>
                                <?php } elseif ($info['type'] === MultiUpload::FIELD_RADIO) { ?>
                                    <?=
                                    Radio::widget([
                                        'model' => $showFile,
                                        'attribute' => $field,
                                        'value' => 1,
                                        'options' => ['class' => 'upload-image-input'] + $options,
                                    ])
                                    ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?=
                                Html::activeTextInput($showFile, $field, [
                                        'class' => 'width-100 upload-image-input',
                                        'placeholder' => $showFile->getAttributeLabel($field),
                                    ] + $options)
                                ?>
                            <?php } ?>
                        </fieldset>
                    <?php } ?>
                    <?= Html::button('Удалить', ['class' => 'button delete-button'] + $options) ?>
                </div>
            </div>
        <?php } ?>

        <?php Pjax::end() ?>

    </div>
</fieldset>
