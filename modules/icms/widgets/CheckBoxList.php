<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use app\modules\icms\widgets\CheckBox;

class CheckBoxList extends InputWidget
{

    public $items = [];
    public $options = [];
    public $select = [];
    public $addHiddenInput = false;

    public function run()
    {
        $options = $this->options;
        if (!isset($options['id'])) {
            $options['id'] = Html::getInputId($this->model, $this->attribute);
        }

        if (!isset($options['name']) && !$this->hasModel()) {
            $options['name'] = $this->name;
        }

        $name = isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $this->attribute);
        $lines = [];
        if ($this->hasModel()) {
            $selection = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $selection = $this->select;
        }
        foreach ($this->items as $key => $nameLabel) {
            $checked = $selection !== null &&
                    (!is_array($selection) && !strcmp($key, $selection) || is_array($selection) && in_array($key, $selection));
            $lines[] = CheckBox::widget([
                        'name' => $name . '[]',
                        'value' => $key,
                        'checked' => $checked,
                        'choiceLabel' => $nameLabel
            ]);
        }
        $html = '';
        if ($this->addHiddenInput) {
            $html .= Html::hiddenInput($name, '');
        }
        $html .= implode("\n", $lines);
        return Html::tag('div', $html, $options);
    }

}
