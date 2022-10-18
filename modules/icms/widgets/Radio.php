<?php

namespace app\modules\icms\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Radio extends \yii\widgets\InputWidget
{

    public $checked;
    public $label = null;

    public function run()
    {

        $labelOptions = ArrayHelper::remove($this->options, 'labelOptions', []);
        $labelClass = ArrayHelper::remove($labelOptions, 'class', '') . ' radio-button';
        $labelOptions['class'] = $labelClass;
        $this->options['labelOptions'] = $labelOptions;

        if (isset($this->options['value']) === false) {
            $this->options['value'] = $this->value;
        }

        if ($this->hasModel() === true) {
            if (is_null($this->label) === true) {
                $this->options['label'] = Html::tag('span', $this->model->getAttributeLabel($this->attribute));
            } else {
                $this->options['label'] = Html::tag('span', $this->label);
            }
            $html = Html::activeRadio($this->model, $this->attribute, $this->options);
        } else {
            $this->options['label'] = Html::tag('span', $this->label);
            $html = Html::radio($this->name, empty($this->checked) === false, $this->options);
        }

        return $html;
    }

}
