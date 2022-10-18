<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use app\components\IcmsHelper;

class CheckBox extends InputWidget
{

    public $checked;
    public $choiceLabel = '';

    public function init()
    {
        $js = <<<JS
$(document).on('change','.blue-check-box', function() {
    if($(this).is(":checked")) {
        $(this).parent().addClass('active');
    }
    else {
        $(this).parent().removeClass('active');
    }
});
JS;
        $this->view->registerJs($js, \yii\web\View::POS_READY, 'check-box-blue');

        $class = 'blue-check-box';
        if (isset($this->options['class'])) {
            $class .= ' ' . $this->options['class'];
        }
        $this->options = \yii\helpers\ArrayHelper::merge($this->options, [
                    'hidden' => 'hidden',
                    'label' => '',
                    'labelOptions' => ['class' => 'chbx']
        ]);
        $this->options['class'] = $class;
        $this->options['label'] = '';

        if ($this->hasModel() && is_null($this->checked)) {
            $this->checked = $this->model->{$this->attribute};
        }
    }

    public function run()
    {
        $html = '';
        $options = $this->options;
        if ($this->hasModel()) {

            if ($this->checked) {
                if ($this->model->isNewRecord) {
                    $this->model->{$this->attribute} = '1';
                }
                $options['value'] = $this->model->isNewRecord ? '1' : $this->model->{$this->attribute};
                $options['labelOptions']['class'] = 'chbx active';
            } else {
                $this->model->{$this->attribute} = 'none';
            }
            $html = Html::activeCheckbox($this->model, $this->attribute, $options);
            if (!empty($this->choiceLabel)) {
                $html .= Html::label($this->choiceLabel, Html::getInputId($this->model, $this->attribute), ['class' => 'text']);
            }
        } else {
            $id = IcmsHelper::clearString($this->name) . '_' . $this->value;

            if ($this->checked) {
                $options['labelOptions']['class'] = 'chbx active';
            }
            $options['value'] = $this->value;
            $options['id'] = $id;
            $html = Html::checkbox($this->name, $this->checked, $options);

            if (!empty($this->choiceLabel)) {
                $html .= Html::label($this->choiceLabel, $id, ['class' => 'text']);
            }

            $html = Html::tag('fieldset', $html);
        }
        return $html;
    }

}
