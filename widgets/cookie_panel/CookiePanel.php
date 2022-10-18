<?php


namespace app\widgets\cookie_panel;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CookiePanel extends Widget
{
    /**
     * @var null|boolean Если true, то будет показываться вне зависимости от кук, если null, будут проверяться куки
     */
    public $isShow = null;

    /**
     * @var array атрибуты блока полоски
     */
    public $options = [
        'class' => 'cookie-panel cookie-panel-block',
    ];

    /**
     * @var string текст блока
     */
    public $text = 'На этом веб-сайте используюся cookie-файлы.';

    /**
     * @var string текст кнопки закрытия блока
     */
    public $closeButtonText = 'Закрыть';

    private $_defaultOptions = [
        'tag' => 'div',
    ];

    public function run()
    {
        if ($this->isShow === false) {
            return '';
        }

        Asset::register($this->getView());

        return $this->_getHtml();
    }

    private function _getHtml()
    {
        $options = ArrayHelper::merge($this->options, $this->_defaultOptions);
        Html::addCssClass($options, 'js-cookie-panel-block');
        Html::addCssStyle($options, 'display:none;');
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        $content = <<<HTML
<div class="cookie-panel-text">{$this->text}</div>
<div class="cookie-panel-close-button js-cookie-panel-close">{$this->closeButtonText}</div>
HTML;

        return Html::tag($tag, $content, $options);
    }

}
