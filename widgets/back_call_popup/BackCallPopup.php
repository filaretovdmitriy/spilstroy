<?php

namespace app\widgets\back_call_popup;

use yii\base\Widget;

class BackCallPopup extends Widget
{

    public $renderButton = true;
    public $addButtonScript = true;
    public $renderPopup = true;
    public $buttonId = 'call-back-button';
    public $popupId = 'call-back-popup';

    public function init()
    {

        BackCallPopupAsset::register($this->view);

        if ($this->renderButton) {
            $js = <<<JS
$('#{$this->buttonId}').on('click', function() {
    $('#{$this->popupId}').popup();
});
JS;
            $this->view->registerJs($js);
        }
    }

    public function run()
    {

        $html = '';
        if ($this->renderButton) {
            $html .= \yii\helpers\Html::tag('div', 'Обратный звонок', [
                        'class' => 'call-back-button',
                        'id' => $this->buttonId,
            ]);
        }

        if ($this->renderPopup) {
            $html .= $this->render('back_call_popup', [
                'model' => new BackCallForm(),
                'popupId' => $this->popupId,
            ]);
        }

        return $html;
    }

}
