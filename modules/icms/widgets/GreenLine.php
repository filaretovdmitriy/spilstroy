<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class GreenLine extends Widget
{

    const SESSION_SHOW_NAME = 'SHOW-GREEN-LINE';

    public function init()
    {

        $js = <<<JS
function showGreenLine(text) {
    if (undefined === text) {
        text = 'Изменения сохранены!'
    }
    line = $('.message.message-success');
    $('.js-green-line-text').text(text);
                
    line.height(0);
    line.show();
    line.animate({
        height: '40px'
    }, 1000);
}

function hideGreenLine() {
    var line = $('.message.message-success');

    line.animate({
        height: '0px'
    }, 1000, function() {
        line.hide();
    });
}
JS;
        $jsClose = <<<JS
$('.message.message-success .close').on('click', function() {
    hideGreenLine();
});
JS;
        $this->view->registerJs($js, \yii\web\View::POS_HEAD);
        $this->view->registerJs($jsClose);
    }

    public function run()
    {
        $options = [
            'class' => 'message message-success',
            'style' => 'display: none'
        ];

        $isShow = \Yii::$app->session->getFlash(self::SESSION_SHOW_NAME, false);

        if (is_array($isShow) === true) {
            $isShow = array_shift($isShow);
        }

        if ($isShow !== false) {
            $options['style'] = false;
        }

        $messageText = is_string($isShow) === true ? $isShow : 'Изменения сохранены!';

        return Html::tag('div', Html::tag('span', '', ['class' => 'close']) . Html::tag('span', $messageText, ['class' => 'green-line-text js-green-line-text']), $options
        );
    }

    /**
     * Показ полоски
     * @param string|boolean $text true - вывод со стандартным текстом. Или текст для вывода на полоске
     */
    static function show($text = true)
    {
        \Yii::$app->getSession()->addFlash(self::SESSION_SHOW_NAME, $text);
    }

}
