<?php

namespace app\modules\icms\widgets\yandex_map;

use yii\widgets\InputWidget;
use yii\helpers\Html;

class YandexMapImageInput extends InputWidget
{

    public $imagePreview = '';
    private $imageName = '';
    public $attributeX = 'image_x';
    public $attributeY = 'image_y';

    public function init()
    {
        if (!$this->imagePreview) {
            $this->imagePreview = $this->model->getPath($this->attribute);
        }
        $this->imageName = $this->model->{$this->attribute};
        if (!empty($this->imageName)) {
            \yii\jui\JuiAsset::register($this->view);
            $idX = Html::getInputId($this->model, $this->attributeX);
            $idY = Html::getInputId($this->model, $this->attributeY);
            $this->view->registerJs(<<<JS
$( "#draggable-ya" ).draggable({
    containment: "#containment-wrapper-ya",
    scroll: false,
    drag: function( event, ui ) {
        $('#{$idY}').val(ui.position.top + 5);
        $('#{$idX}').val(ui.position.left + 5);
    }
});
JS
            );
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
});
JS;
        $this->view->registerJs($jsFileInput, \yii\web\View::POS_READY, 'file-input-view-file-name-and-size');
        $this->view->registerJs($js, \yii\web\View::POS_READY, 'file-image-input');
    }

    public function run()
    {
        return $this->render('yandex_file_image_input', [
                    'imageName' => $this->imageName,
                    'imagePreview' => $this->imagePreview,
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'attributeX' => $this->attributeX,
                    'attributeY' => $this->attributeY,
        ]);
    }

}
