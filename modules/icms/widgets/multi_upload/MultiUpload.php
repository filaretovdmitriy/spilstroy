<?php

namespace app\modules\icms\widgets\multi_upload;

use app\components\IcmsHelper;
use yii\helpers\Url;
use yii\validators\FileValidator;

class MultiUpload extends \yii\base\Widget
{

    const TYPE_IMAGES = 1;
    const TYPE_FILES = 2;
    const FIELD_TEXTAREA = 1;
    const FIELD_CHECKBOX = 2;
    const FIELD_RADIO = 3;
    const DEFAULT_REAL_NAME = '{{REALNAME}}';

    /**
     * Название модели в которой будут храниться изображения
     * @var string
     */
    public $modelName;

    /**
     * Название поля изображения/файла
     * @var string
     */
    public $field;

    /**
     * Название поля модели, по которому производится связь
     * @var string
     */
    public $relationField;

    /**
     * Значения поля связи
     * @var integer
     */
    public $relationValue;

    /**
     * Позволять ли изменять размер на лету
     * @var boolean
     */
    public $resize = true;

    /**
     * Тип файла. Файл (TYPE_IMAGES) или изображение (TYPE_FILES)
     * @var integer
     */
    public $type;

    /**
     * Поля для сохранения<br>
     * Если элемент не массив, то это будет просто текстовый инпут<br>
     * Пример:<br>
     * [<br>
     *     'name' => 'Изображение',<br>
     *     'name' => MultiUpload::DEFAULT_REAL_NAME, // Имя файла без расширения<br>
     *     'sort' => 0,<br>
     *     'text' => ['value' => 'Текст по умолчанию', 'type' => FIELD_TEXTAREA],<br>
     *     'is_show' => ['value' => false, 'type' => FIELD_CHECKBOX],<br>
     * ]<br>
     * @var array
     */
    public $fields = [];

    /**
     * Заголовок виджета
     * @var string
     */
    public $label = false;

    /**
     * id pjax'а
     * @var string
     */
    public $pjaxId;

    /**
     * Адрес для обновления открытого fancyBox
     * @var type
     */
    public $updateFancy;

    private function _cropperJs()
    {
        $js = <<<JS
$('.cropper-block').on('change','.cropper', function() {
    elem = $(this);
    if (elem.is(':checked')) {
        $(this).parents('.cropper-block').find('input[type=text]').removeAttr('disabled');
    } else {
        $(this).parents('.cropper-block').find('input[type=text]').attr('disabled', true);
    }
});
JS;
        $this->view->registerJs($js, \yii\web\View::POS_READY, 'crop-fields-on-off');
    }

    private function _uploaderJs()
    {
        jQueryUploadAssets::register($this->view);

        $updateFancy = $this->updateFancy ?: '';

        $uploadUrl = Url::to(['ajax/upload_file']);

        $js = <<<JS
$('#multi-upload-input-{$this->id}').fileupload({
    dataType: 'json',
    url: '{$uploadUrl}',
    sequentialUploads: true,
    autoUpload: true,
    submit: function (e, data) {
        data.formData = $('#fake-upload-form-{$this->id} input').serializeArray();
    },
    start: function(e, data) {
        bar = $('#multi-upload-input-block-{$this->id} .progress .bar');
        bar.html('0%');
        bar.css('width', '0%');
        errorBlock = $('#{$this->id} .error-block');
        errorBlock.hide();
        errorBlock.html('<div class="error-block-title">Ошибки:</div>');
    },
    progressall: function (e, data) {
        progress = parseInt(data.loaded / data.total * 100, 10);
        bar = $('#multi-upload-input-block-{$this->id} .progress .bar');
        bar.html(progress + '%');
        bar.css('width', progress + '%');

        if (progress >= 100) {
            bar.html('');
            bar.css('width', '0%');
            $.gritter.add({title: 'Загружено', text: 'Загрузка изображений завершена'});

            if ('{$updateFancy}'.length > 0) {
                $.post('{$updateFancy}', {}, function(html) {
                    $.fancybox.wrap.find('.fancybox-inner').html(html);
                    $.fancybox.update();
                }, 'html');
                
                return;
            }

            $.pjax.reload('#{$this->pjaxId}');
        }
    },
    done: function(e, data) {
        if (data.result.success === false) {
            var errorBlock = $('#{$this->id} .error-block');
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
JS;
        $this->view->registerJs($js);
    }

    private function _editFileJs()
    {
        $removeUrl = Url::to(['ajax/delete_multi']);
        $editUrl = Url::to(['ajax/save_multi']);
        $js = <<<JS
$('#{$this->id}').on('click', '.delete-button', function() {
    if (confirm('Удалить?') === false) {
        return false;
    }
    var id = $(this).data('id');
    model = $(this).data('model');
    var prefix = $(this).data('prefix');
    var self = this;
    $('#preloader').show();
    $.post('{$removeUrl}', {id: id, model: model}, function(data){
        if (data.error == undefined) {
            $.gritter.add({title: 'Удалено', text: data.text})
            $(self).closest('.uppic-container').remove();
        } else {
            $.gritter.add({title: 'Ошибка', text: data.error})
        }
        $('#preloader').hide();
    }, 'json').fail(function() {
        $('#preloader').hide();
    });

    return false;
});
$('#{$this->id}').on('change', '.upload-image-input', function() {
    $('#preloader').show();
    var id = $(this).data('id');
    model = $(this).data('model');
    params = $(this).closest('.js-file-inputs').find('input, textarea, select').serialize();
    params = params + '&model=' + model + '&id=' + id;
    $.post('{$editUrl}', params, function(data) {
        if (data.error == undefined) {
            $.gritter.add({title: 'Сохранено', text: data.text})
        } else {
            $.gritter.add({title: 'Ошибка', text: data.error})
        }
        $('#preloader').hide();
    }, 'json').fail(function() {
        $('#preloader').hide();
    });
});
JS;

        $this->view->registerJs($js);
    }

    public function init()
    {
        if (is_null($this->type) === true) {
            throw new \yii\web\ServerErrorHttpException('Не задан тип загрузчика (файлы или изображения)');
        }

        $this->resize = extension_loaded('gd') && $this->resize;

        if ($this->type === self::TYPE_FILES) {
            $this->resize = false;
        }

        if (empty($this->pjaxId) === true) {
            $this->pjaxId = 'multi-pjax-' . $this->getId();
        }

        if ($this->resize === true) {
            $this->_cropperJs();
        }

        $this->_uploaderJs();
        $this->_editFileJs();
    }



    private function _getMaxFileSize()
    {

        $model = new $this->modelName;
        $validators = $model->getActiveValidators($this->field);
        $maxSize = 0;
        foreach ($validators as $validator) {
            if ($validator instanceof FileValidator === false) {
                continue;
            }
            /* @var $validator FileValidator */
            if (is_null($validator->maxSize) === false && $validator->maxSize > 0) {
                $maxSize = $validator->maxSize;
            }
        }

        $phpOptionMaxSize = IcmsHelper::getFileUploadMaxSize();
        if ($maxSize > $phpOptionMaxSize || $maxSize === 0) {
            return $phpOptionMaxSize;
        }

        return $maxSize;
    }

    public function run()
    {
        $file = new $this->modelName;

        if (!($file instanceof \app\components\db\ActiveRecordFiles)) {
            throw new \yii\web\ServerErrorHttpException('Модель не поддерживает работу с файлами');
        }

        $file->load($this->fields, '');

        return $this->render('multi_upload', [
            'file' => $file,
            'resize' => $this->resize,
            'type' => $this->type,
            'relationField' => $this->relationField,
            'relationValue' => $this->relationValue,
            'fields' => $this->fields,
            'label' => $this->label,
            'id' => $this->getId(),
            'pjaxId' => $this->pjaxId,
            'fileField' => $this->field,
            'maxFileSize' => $this->_getMaxFileSize(),
        ]);
    }

}
