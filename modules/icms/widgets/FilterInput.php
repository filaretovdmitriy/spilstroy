<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;

class FilterInput extends InputWidget
{

    public $placeholder = '';

    public function init()
    {
        $js = <<<JS
$('.filter-input').on('click', '.filter-button.clean', function() {
    input = $(this).parent().children('input')
    input.val('');
    input.change();
});
$('.filter-input').on('click', '.filter-button.search', function() {
    input = $(this).parent().children('input')
    input.change();
});
JS;
        $this->view->registerJs($js, \yii\web\View::POS_READY, 'filter-input');
    }

    public function run()
    {
        $html = Html::tag('div', Html::activeTextInput($this->model, $this->attribute, ['class' => 'filter-input-text', 'placeholder' => $this->placeholder]) .
                        Html::tag('i', '×', ['class' => 'filter-button clean', 'title' => 'Отчистить']) .
                        Html::tag('i', '', ['class' => 'filter-button search', 'title' => 'Фильтровать']), ['class' => 'filter-input']
        );
        return $html;
    }

}
