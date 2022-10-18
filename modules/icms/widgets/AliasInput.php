<?php

namespace app\modules\icms\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use app\modules\icms\widgets\CheckBox;

class AliasInput extends InputWidget
{

    public $from;
    public $autoEnabled;
    public $autoEnabledField = null;
    public $displayChoise = true;

    public function init()
    {
        if (is_null($this->autoEnabled) && !is_null($this->autoEnabledField)) {
            $this->autoEnabled = $this->model->{$this->autoEnabledField} ? true : false;
        }
    }

    public function run()
    {

        $aliasInputId = Html::getInputId($this->model, $this->attribute);
        $aliasDivId = 'alias-check-box-' . $aliasInputId . '-' . $this->from;

        if (!$this->autoEnabled && !$this->displayChoise) {
            return Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $scriptReadOnly = <<<JS
function generateAlias (str) {
    str = str.toLowerCase();
    str = str.replace(/[^\a-z|^а-я|^\d|^_|^-]/gi, ' ').trim();
    var cyr2latChars = new Array(
        ['а', 'a'], ['б', 'b'], ['в', 'v'], ['г', 'g'],
        ['д', 'd'],  ['е', 'e'], ['ё', 'yo'], ['ж', 'zh'], ['з', 'z'],
        ['и', 'i'], ['й', 'y'], ['к', 'k'], ['л', 'l'],
        ['м', 'm'],  ['н', 'n'], ['о', 'o'], ['п', 'p'],  ['р', 'r'],
        ['с', 's'], ['т', 't'], ['у', 'u'], ['ф', 'f'],
        ['х', 'h'],  ['ц', 'c'], ['ч', 'ch'],['ш', 'sh'], ['щ', 'shch'],
        ['ъ', ''],  ['ы', 'y'], ['ь', ''],  ['э', 'e'], ['ю', 'yu'], ['я', 'ya'],

        ['a', 'a'], ['b', 'b'], ['c', 'c'], ['d', 'd'], ['e', 'e'],
        ['f', 'f'], ['g', 'g'], ['h', 'h'], ['i', 'i'], ['j', 'j'],
        ['k', 'k'], ['l', 'l'], ['m', 'm'], ['n', 'n'], ['o', 'o'],
        ['p', 'p'], ['q', 'q'], ['r', 'r'], ['s', 's'], ['t', 't'],
        ['u', 'u'], ['v', 'v'], ['w', 'w'], ['x', 'x'], ['y', 'y'],
        ['z', 'z'],

        [' ', '-'],['0', '0'],['1', '1'],['2', '2'],['3', '3'],
        ['4', '4'],['5', '5'],['6', '6'],['7', '7'],['8', '8'],['9', '9'],
        ['-', '-'],['_', '_']
    );
    var newStr = new String();
    for (var i = 0; i < str.length; i++) {
        ch = str.charAt(i);
        var newCh = '';
        for (var j = 0; j < cyr2latChars.length; j++) {
            if (ch == cyr2latChars[j][0]) {
                newCh = cyr2latChars[j][1];

            }
        }
        newStr += newCh;
    }
    clearString = newStr.replace(/[_]{2,}/gim, '_').replace(/[-]{2,}/gim, '-');
    return clearString;
}
JS;
            $this->view->registerJs($scriptReadOnly, \yii\web\View::POS_READY, 'alias-check-box');

            $script = <<<JS
$('#$aliasDivId').on('change', '.alias-check-box input', function() {
    input = $('#$aliasInputId');
    if ($(this).prop('checked')) {
         input.attr('readonly', 'true');
         input.val(generateAlias($('#{$this->from}').val()));
    } else {
         input.removeAttr('readonly');
    }
});
$('body').on('keyup', '#{$this->from}', function() {
    if ($('#$aliasDivId .alias-check-box input:checked').length > 0) {
        $('#$aliasInputId').val(generateAlias($(this).val()));
    }
})
JS;

            $this->view->registerJs($script, \yii\web\View::POS_READY, $aliasDivId);
        }

        if ($this->displayChoise) {

            $options = [
                'class' => 'alias-text-input',
                'readonly' => $this->autoEnabled
            ];

            $html = Html::activeTextInput($this->model, $this->attribute, $options);
            if (is_null($this->autoEnabledField)) {
                $html .= Html::tag('div', CheckBox::widget([
                                    'name' => '',
                                    'checked' => $this->autoEnabled,
                                    'value' => 1,
                                    'choiceLabel' => 'Авто'
                                ]), ['class' => 'alias-check-box']);
            } else {
                $html .= Html::tag('div', CheckBox::widget([
                                    'model' => $this->model,
                                    'attribute' => $this->autoEnabledField,
                                    'checked' => $this->autoEnabled,
                                    'choiceLabel' => 'Авто'
                                ]), ['class' => 'alias-check-box']);
            }
            return Html::tag('div', $html, ['id' => $aliasDivId]);
        }
    }

}
