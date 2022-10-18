<?php

namespace app\modules\icms\widgets;

use app\components\IcmsHelper;
use yii\validators\FileValidator;
use yii\web\View;
use yii\widgets\InputWidget;

class FileImageInput extends InputWidget
{

    public $imagePreview = '';
    public $resize = true;
    private $imageName = '';

    public function init()
    {
        $this->resize = extension_loaded('gd') && $this->resize;
        if (!$this->imagePreview) {
            $this->imagePreview = $this->model->getPath($this->attribute);
        }
        $this->imageName = $this->model->{$this->attribute};
        if (is_string($this->imageName) === false) {
            $this->imageName = '';
        }

        $js = <<<JS
$('.del_pic').click(function () {
    lnk = $(this);
    var arr_id = $(this).attr('id').split("-");
    $.post('/icms/ajax/delete_picture', {"table": arr_id[0], "id_elem": arr_id[2], "field": arr_id[1]}, function (data) {
        if (data.success) {
            lnk.parent().parent().parent().find('.img_load').slideDown();
            lnk.parent().parent().slideUp();
        }
    }, "json");
});
JS;

        $jsFileInput = <<<JS
$('label.file input').change(function () {
    var elem = $(this);
    var filename = elem.val().replace(/.+[\\\/]/, "");
    elem.prev().prev().html(filename);
    var file, img;
        
    if ((file = this.files[0])) {
        if (elem.parents('.image-crop').find('.crop-width').val() === '' && elem.parents('.image-crop').find('.crop-height').val() === '') {
            var url = window.URL || window.webkitURL;
            img = new Image();
            img.onload = function () {
                elem.parents('.image-crop').find('.crop-width').val(this.width);
                elem.parents('.image-crop').find('.crop-height').val(this.height);
            };
            img.src = url.createObjectURL(file);
        }
    }
});
JS;
        if ($this->resize) {
            $jsCropFields = <<<JS
$('.cropper-block').on('change','.cropper', function() {
    elem = $(this);
    if (elem.is(':checked')) {
        $(this).parents('.cropper-block').find('input[type=text]').removeAttr('disabled');
    } else {
        $(this).parents('.cropper-block').find('input[type=text]').attr('disabled', true);
    }
});
JS;
            $this->view->registerJs($jsCropFields, View::POS_READY, 'crop-fields-on-off');
        }
        $this->view->registerJs($jsFileInput, View::POS_READY, 'file-input-view-file-name-and-size');
        $this->view->registerJs($js, View::POS_READY, 'file-image-input');
    }

    private function _getMaxFileSize()
    {

        $maxSize = 0;
        if ($this->hasModel() === true) {
            $validators = $this->model->getActiveValidators($this->attribute);
            foreach ($validators as $validator) {
                if ($validator instanceof FileValidator === false) {
                    continue;
                }
                /* @var $validator FileValidator */
                if (is_null($validator->maxSize) === false && $validator->maxSize > 0) {
                    $maxSize = $validator->maxSize;
                }
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
        return $this->render('file_image_input', [
            'imageName' => $this->imageName,
            'imagePreview' => $this->imagePreview,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'resize' => $this->resize,
            'maxFileSize' => $this->_getMaxFileSize(),
        ]);
    }

}
