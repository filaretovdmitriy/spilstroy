<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use yii\base\Exception;
use yii\helpers\Html;

class Tabs extends Widget
{

    public $tabNames = [];
    public $activeTab = null;
    public $idPrefix;
    public $openLast = true;
    private $tabIterator = 0;
    private $tabIds = [];
    private $openTab = false;
    private $closeTab = true;
    private $_obJS = null;
    private $_obCSS = null;
    private $_obBundles = null;

    public function init()
    {
        if (empty($this->idPrefix)) {
            $this->idPrefix = $this->id . '-';
        }
    }

    private function nextId()
    {
        $this->tabIterator = next($this->tabIds);
    }

    /**
     * Начало рендера виджета
     * @param array $config настройки
     * @return \app\modules\icms\widgets\Tabs
     */
    public static function begin($config = array())
    {
        $widget = parent::begin($config);


        if (isset($config['tabNames'])) {
            $widget->tabNames = $config['tabNames'];
        } else {
            throw new Exception('Названия табов не определены');
        }

        if ($widget->openLast) {
            $widget->activeTab = str_replace($widget->idPrefix, '', \Yii::$app->request->getBodyParam($widget->idPrefix . 'current-tab', ''));
        }

        if (isset($config['activeTab']) && empty($widget->activeTab)) {
            $widget->activeTab = $config['activeTab'];
        }

        foreach ($widget->tabNames as $tabId => $tabName) {
            if ((is_null($widget->activeTab) || $widget->activeTab === '') && $tabName !== false) {
                $widget->activeTab = $tabId;
            }
            $widget->tabIds[] = $tabId;
        }

        if (!in_array($widget->activeTab, $widget->tabIds)) {
            throw new Exception('Активного таба (id:' . $widget->activeTab . ') нет в списке табов');
        }

        if (isset($config['idPrefix'])) {
            $widget->idPrefix = $config['idPrefix'];
        }

        $widget->tabIterator = current($widget->tabIds);

        echo Html::beginTag('div', ['class' => 'tabs', 'id' => $widget->idPrefix . 'container']);

        if ($widget->openLast) {
            echo Html::hiddenInput($widget->idPrefix . 'current-tab', $widget->idPrefix . $widget->activeTab, ['id' => $widget->idPrefix . 'current-tab']);
        }

        echo Html::beginTag('ul', ['id' => $widget->idPrefix . 'container-names']);
        foreach ($widget->tabNames as $tabId => $tabName) {
            if ($tabName === false) {
                continue;
            }
            echo Html::beginTag('li', [
                'class' => $tabId == $widget->activeTab ? 'active' : ''
            ]);
            echo Html::a($tabName, '#' . $widget->idPrefix . $tabId, ['data-tab' => $widget->idPrefix . $tabId]);
            echo Html::endTag('li');
        }
        echo Html::endTag('ul');

        $view = $widget->getView();
        $view->registerJs(<<<JS
$('#{$widget->idPrefix}container').on('click', '#{$widget->idPrefix}container-names li a', function() {
    $('#{$widget->idPrefix}container-names li').removeClass('active');
    $(this).parent().addClass('active');
    tabId = $(this).data('tab');
    if ($('#{$widget->idPrefix}current-tab').length > 0) {
        $('#{$widget->idPrefix}current-tab').val(tabId);
    }
    $('#{$widget->idPrefix}container .tab_item').hide();
    $('#tab-' + tabId).show();
});
$(window).bind('hashchange', function() {
    setTaberHashActive_{$widget->id}();
});
function setTaberHashActive_{$widget->id}() {
    if (location.hash) {
        tabLink = location.hash.replace('#', '');
        link = $('#{$widget->idPrefix}container-names a[data-tab="' + tabLink + '"]');
        if (link.length === 1) {
            link.trigger('click');
        }
    }
}
setTaberHashActive_{$widget->id}();
JS
        );

        return $widget;
    }

    /**
     * Открывает таб
     * @param boolean $renderInputId рендерить ли скрытый инпут для открытия этой вкладки
     */
    public function beginTab($renderInputId = false)
    {
        if ($this->openTab) {
            throw new Exception('Ошибка порядка. Таб уже был открыт!');
        }
        if (!$this->closeTab) {
            throw new Exception('Ошибка порядка. Не закрыт предыдущий таб!');
        }
        if ($this->tabNames[$this->tabIterator] === false) {
            ob_start();
            $this->_obJS = \Yii::$app->view->js;
            $this->_obCSS = \Yii::$app->view->css;
            $this->_obBundles = \Yii::$app->view->assetBundles;
        }


        echo Html::beginTag('div', [
            'class' => 'tab_item',
            'id' => 'tab-' . $this->idPrefix . $this->tabIterator,
            'style' => $this->activeTab == $this->tabIterator ? 'display: block' : 'display: none'
        ]);
        if ($this->openLast && $renderInputId === true) {
            echo Html::hiddenInput($this->idPrefix . 'current-tab', $this->idPrefix . $this->tabIterator);
        }
        $this->openTab = true;
        $this->closeTab = false;
    }

    /**
     * Конец вкладки
     */
    public function endTab()
    {
        if (!$this->openTab) {
            throw new Exception('Ошибка порядка. Предыдущий таб не открыт!');
        }
        if ($this->closeTab) {
            throw new Exception('Ошибка порядка. Нет окрытых табов!');
        }
        echo Html::endTag('div');
        if ($this->tabNames[$this->tabIterator] === false) {
            \Yii::$app->view->js = $this->_obJS;
            \Yii::$app->view->css = $this->_obCSS;
            \Yii::$app->view->assetBundles = $this->_obBundles;
            ob_end_clean();
        }
        $this->nextId();

        $this->openTab = false;
        $this->closeTab = true;
    }

    public static function end()
    {
        echo Html::endTag('div');
        parent::end();
    }

}
