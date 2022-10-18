<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;

class CheckBoxSlide extends InputWidget
{

    public $choiceLabel = '';
    public $checked = null;

    public function init()
    {

        $js = <<<JS
$(document).on('change','.slide-check-box', function() {
    if($(this).is(":checked")) {
        $(this).parent().parent().addClass('active');
    }
    else {
        $(this).parent().parent().removeClass('active');
    }
});
JS;
        $this->view->registerJs($js, \yii\web\View::POS_READY, 'check-box-slide');
    }

    public function run()
    {
        return $this->render('check_box_slide', [
                    'choiceLabel' => $this->choiceLabel,
                    'checked' => $this->checked,
                    'name' => $this->name,
                    'model' => $this->model,
                    'attribute' => $this->attribute
        ]);
    }

}
