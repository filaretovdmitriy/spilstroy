<?php

namespace app\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class RadioList extends InputWidget
{

    public $items = [];
    public $options = [];
    public $select = null;
    public $itemsOptions = [];

    public function run()
    {
        $options = $this->options;
        if (!isset($options['id'])) {
            $options['id'] = Html::getInputId($this->model, $this->attribute);
        }
        $name = isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $this->attribute);
        $lines = [];
        if ($this->hasModel()) {
            $selection = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $selection = [$this->select];
        }
        foreach ($this->items as $key => $nameLabel) {
            $itemOption = ArrayHelper::merge([
                        'value' => $key,
                        'label' => '<i class="rounded-x"></i>' . $nameLabel,
                        'labelOptions' => [
                            'class' => 'radio'
                        ]
                            ], isset($this->itemsOptions[$key]) ? $this->itemsOptions[$key] : []);
            $checked = $selection !== null &&
                    (!is_array($selection) && !strcmp($key, $selection) || is_array($selection) && in_array($key, $selection));
            $lines[] = Html::radio($name, $checked, $itemOption);
        }

        return Html::tag('div', implode("\n", $lines), $options);
    }

}
