<?php

namespace app\modules\icms\widgets\drop_down_list;

use yii\widgets\InputWidget;
use app\modules\icms\widgets\drop_down_list\DropDownListAssets;
use yii\helpers\Html;
use yii\helpers\Json;

class DropDownList extends InputWidget
{

    public $selection = null;
    public $name;
    public $divClass = "";
    public $divDataTip = "";
    public $items = [];
    public $placeholder = '';
    public $width = '100%';
    public $targetClass = 'select2-drop-list';
    public $parameters = [];
    public $clientEvents = [];

    public function init()
    {
        parent::init();
        $this->registerAssets();
    }

    private function _getEventsJs($target)
    {
        if (empty($this->clientEvents)) {
            return '';
        }
        $js = '';
        if (isset($this->clientEvents['open'])) {
            $js .= "$('{$target}').on('select2:open', function(e) {{$this->clientEvents['open']}});";
        }
        if (isset($this->clientEvents['close'])) {
            $js .= "$('{$target}').on('select2:close', function(e) {{$this->clientEvents['close']}});";
        }
        if (isset($this->clientEvents['select'])) {
            $js .= "$('{$target}').on('select2:select', function(e) {{$this->clientEvents['select']}});";
        }
        if (isset($this->clientEvents['unselect'])) {
            $js .= "$('{$target}').on('select2:unselect', function(e) {{$this->clientEvents['unselect']}});";
        }
        if (isset($this->clientEvents['change'])) {
            $js .= "$('{$target}').on('change', function(e) {{$this->clientEvents['change']}});";
        }

        return $js;
    }

    public function run()
    {

        $attributes = \yii\helpers\ArrayHelper::merge([
                    'style' => "width: {$this->width}"
                        ], $this->options);

        $parameters = $this->parameters;

        if (!empty($this->placeholder)) {
            $parameters['placeholder'] = $this->placeholder;
            $parameters['allowClear'] = true;
        }
        $parameters['language'] = 'ru';
        if (count($this->items) < 8) {
            $parameters['minimumResultsForSearch'] = 'Infinity';
        }
        $paramsString = Json::encode($parameters);

        if ($this->hasModel()) {
            $target = '#' . $this->getId();
        } else {
            $target = '.' . $this->targetClass;
            $class = \yii\helpers\ArrayHelper::remove($attributes, 'class', '');
            $attributes['class'] = $this->targetClass . ' ' . $class;
        }

        $js = <<<JS
$('{$target}').next().remove();
$('{$target}').select2({$paramsString});
JS;


        $this->view->registerJs($js . $this->_getEventsJs($target));


        $attributes['id'] = $this->getId();
        if ($this->hasModel()) {
            return Html::tag('div', Html::activeDropDownList($this->model, $this->attribute, $this->items, $attributes), ['class' => $this->divClass]);
        } else {

            return Html::tag('div', Html::dropDownList($this->name, $this->selection, $this->items, $attributes), ['class' => $this->divClass]);
        }
    }

    public function registerAssets()
    {
        $view = $this->getView();
        DropDownListAssets::register($view);
    }

}
