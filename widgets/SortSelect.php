<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class SortSelect extends Widget
{

    public $sort = null;
    public $options = [];

    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId() . '-filter-sortable';
        }

        $js = <<<JS
$('#{$this->options['id']}').on('change', function() {
    url = $(this).find(':checked').data('url');
    window.location = url;
});
JS;

        $this->view->registerJs($js);
    }

    public function run()
    {

        $items = [];
        foreach ($this->sort->attributes as $attributeName => $parameters) {
            if (!isset($parameters['label'])) {
                continue;
            }
            $items[$attributeName] = $parameters['label'];
            $this->options['options'][$attributeName] = ['data-url' => $this->sort->createUrl($attributeName)];
        }

        return Html::dropDownList($this->getId() . '-filter-sortable', array_keys($this->sort->getOrders()), $items, $this->options);
    }

}
