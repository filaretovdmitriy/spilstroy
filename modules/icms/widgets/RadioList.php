<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use app\modules\icms\widgets\Radio;

class RadioList extends InputWidget
{

    public $items = [];
    public $options = [];
    public $select = null;

    public function run()
    {
        $options = $this->options;
        if (!isset($options['id'])) {
            $options['id'] = Html::getInputId($this->model, $this->attribute);
        }
        if (empty($this->name)) {
            $name = isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $this->attribute);
        } else {
            $name = $this->name;
        }
        $lines = [];
        if ($this->hasModel()) {
            $selection = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $selection = [$this->select];
        }
        foreach ($this->items as $key => $nameLabel) {
            $checked = $selection !== null &&
                    (!is_array($selection) && !strcmp($key, $selection) || is_array($selection) && in_array($key, $selection));
            $lines[] = Html::tag('div', Radio::widget([
                                'name' => $name,
                                'value' => $key,
                                'checked' => $checked,
                                'label' => $nameLabel
                            ]), ['class' => 'clearfix radio-list-element']);
        }

        return Html::tag('div', implode("\n", $lines), $options);
    }

}
