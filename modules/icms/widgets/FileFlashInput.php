<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;
use app\components\db\ActiveRecordFiles;

class FileFlashInput extends InputWidget
{

    private $fileName;
    private $imagePreview;

    public function init()
    {
        parent::init();
        $this->imagePreview = $this->model->getPath($this->attribute, ActiveRecordFiles::TYPE_FILE_FILE);
        $this->fileName = $this->model->{$this->attribute};
        if (is_string($this->fileName) === false) {
            $this->fileName = '';
        }

        $js = <<<JS
$('.del_pic').click(function () {
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

        $jsFileInput = <<<JS
$('label.file input').change(function () {
    var filename = $(this).val().replace(/.+[\\\/]/, "");
    $(this).prev().prev().html(filename);
});
JS;

        $this->view->registerJs($jsFileInput, \yii\web\View::POS_READY, 'file-input-view-file-name');
        $this->view->registerJs($js, \yii\web\View::POS_READY, 'file-flash-input');
    }

    public function run()
    {
        return $this->render('file_flash_input', [
                    'imageName' => $this->fileName,
                    'imagePreview' => $this->imagePreview,
                    'model' => $this->model,
                    'attribute' => $this->attribute
        ]);
    }

}
