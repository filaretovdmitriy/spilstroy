<?php

namespace app\modules\icms\widgets;

use app\components\IcmsHelper;
use yii\validators\FileValidator;
use yii\widgets\InputWidget;

class FileInput extends InputWidget
{

    public $filePath = '';
    private $fileName = '';

    public function init()
    {
        parent::init();
        if ($this->hasModel()) {
            if (empty($this->filePath)) {
                $model = $this->model;
                $this->filePath = $this->model->getPath($this->attribute, $model::TYPE_FILE_FILE);
            }
            $this->fileName = $this->model->{$this->attribute};
            if (is_string($this->fileName) === false) {
                $this->fileName = '';
            }
            $js = <<<JS
$('.del_file').click(function () {
    lnk = $(this);
    var arr_id = $(this).attr('id').split("-");
    $.post('/icms/ajax/delete_file', {"table": arr_id[0], "id_elem": arr_id[2], "field": arr_id[1]}, function (data) {
        if (data.success) {
            lnk.parent().parent().parent().find('.img_load').slideDown();
            lnk.parent().parent().slideUp();
        }
    }, "json");
});
JS;
            $this->view->registerJs($js, \yii\web\View::POS_READY, 'file-input-delete');
        }



        $jsFileInput = <<<JS
$('label.file input').change(function () {
    var filename = $(this).val().replace(/.+[\\\/]/, "");
    $(this).parent().find('.val').html(filename);
});
JS;
        $this->view->registerJs($jsFileInput, \yii\web\View::POS_READY, 'file-input-view-file-name');
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
        return $this->render('file_input', [
                    'fileName' => $this->fileName,
                    'filePath' => $this->filePath,
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'name' => $this->name,
                    'hasModel' => $this->hasModel(),
                    'maxFileSize' => $this->_getMaxFileSize(),
        ]);
    }

}
