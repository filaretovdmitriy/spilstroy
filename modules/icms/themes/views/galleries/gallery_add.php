<?php
use app\modules\icms\widgets\ActiveFormIcms;
use app\models\GalleryCategorie;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use yii\helpers\Html;

app\modules\icms\widgets\multi_upload\jQueryUploadAssets::register($this);
$this->registerJs(<<<JS
$('#multi-gallery-upload-input').fileupload({
    dataType: 'json',
    url: '/icms/ajax/upload_file',
    sequentialUploads: true,
    autoUpload: true,
    submit: function (e, data) {
        if ($('#upload-gallery-form .has-error').length > 0) {
            $.gritter.add({title: 'Ошибка', text: 'Заполните все поля'});
            return false;
        }
        
        data.formData = $('#upload-gallery-form').serializeArray();
    },
    start: function(e, data) {
        bar = $('#multi-gallery-upload-input-block .progress .bar');
        bar.html('0%');
        bar.css('width', '0%');
        errorBlock = $('#upload-gallery-form .error-block');
        errorBlock.hide();
        errorBlock.html('<div class="error-block-title">Ошибки:</div>');
    },
    progressall: function (e, data) {
        progress = parseInt(data.loaded / data.total * 100, 10);
        bar = $('#multi-gallery-upload-input-block .progress .bar');
        bar.html(progress + '%');
        bar.css('width', progress + '%');

        if (progress >= 100) {
            bar.html('');
            bar.css('width', '0%');
            $.gritter.add({title: 'Загружено', text: 'Загрузка изображений завершена'});
        }
    },
    done: function(e, data) {
        if (data.result.success === false) {
            var errorBlock = $('#upload-gallery-form .error-block');
            html = '<div class="error-block-file-name">' + data.files[0].name + '</div>';

            for (attr in data.result.errors) {
                html += '<div>' + data.result.errors[attr] + '</div>';
            }

            errorBlock.append(html);
            errorBlock.show();
        }
    },
    fail: function(e, data) {
        fileName = data.files[0].name;
        $.gritter.add({title: 'Ошибка', text: 'Не удалось загрузить файл ' + fileName, sticky: true});
    }
});
JS
);
?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin(['id' => 'upload-gallery-form']);
    ?>
    <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация']
        ]);
        ?>

        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'gallery_categorie_id')->widget(DropDownList::class, ['items' => GalleryCategorie::getNamedTreeAsArray(), 'placeholder' => 'Выберите раздел'])->label('Раздел') ?>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100', 'value' => 'Фотография'])->label('Название') ?>

                <?= Html::hiddenInput('model', get_class($model)) ?>
                <fieldset class="uploader-wrapper" id="multi-gallery-upload-input-block">
                    <label>Изображения</label>
                    <div class="progress"><div class="bar" style="width: 0%;"></div></div>
                    <label class="upload-button" for="multi-gallery-upload-input">
                        Обзор
                        <?= Html::activeFileInput($model, 'image', [
                            'class' => 'btn',
                            'multiple' => true,
                            'hidden' => true,
                            'id' => 'multi-gallery-upload-input',
                        ]) ?>
                    </label>
                </fieldset>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100', 'value' => '100'])->label('Сортировка') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <div class="clear"></div>
    <?php ActiveFormIcms::end() ?>
</div>
