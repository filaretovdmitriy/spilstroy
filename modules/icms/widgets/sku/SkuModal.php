<?php

namespace app\modules\icms\widgets\sku;

use yii\base\Widget;
use yii\helpers\Html;
use app\modules\icms\widgets\fancy_box\FancyBox;

class SkuModal extends Widget
{

    public $buttonId = 'create-new-sku';
    public $buttonTarget = '.sku-action-edit';
    public $catalogId;
    public $edit = false;
    private $_js;

    public function init()
    {
        $this->_js = <<<JS
function(current) {
    function findAll(elems, selector) {
        return elems.filter(selector).add(elems.find(selector));
    }
    contents = $($.parseHTML(current.content, document, true));
    scripts = findAll(contents, 'script[src]').remove();
    css = findAll(contents, 'link[href][rel="stylesheet"]').remove();
    contents = contents.not(scripts);
    contents = contents.not(css);
    if (scripts) {
        var existingScripts = $('script[src]');
        scripts.each(function() {
            var src = this.src;
            var matchedScripts = existingScripts.filter(function() {
                return this.src === src;
            })
            if (matchedScripts.length) return;
  
            var script = document.createElement('script');
            script.src = src;
            $('.fancybox-wrap').before(script);
        })
    }
    if (css) {
        var existingCss = $('link[href][rel="stylesheet"]')
        css.each(function() {
            var href = this.href;
            var matchedCss = existingCss.filter(function() {
                return this.href === href
            })
            if (matchedCss.length) return;

            var link = document.createElement('link')
            link.rel = 'stylesheet'
            link.href = $(this).attr('href')
            document.head.appendChild(link)
        })  
    }
    current.content = contents;
}
JS;
    }

    private function renderAdd()
    {
        $html = Html::a('Добавить', ['catalogajax/sku', 'catalog_id' => $this->catalogId], [
                    'class' => 'button',
                    'id' => $this->buttonId
        ]);
        return $html . FancyBox::widget([
                    'target' => '#' . $this->buttonId,
                    'afterLoad' => $this->_js,
                    'config' => [
                        'type' => 'ajax'
                    ]
        ]);
    }

    private function renderEdit()
    {
        return FancyBox::widget([
                    'target' => $this->buttonTarget,
                    'afterLoad' => $this->_js,
                    'config' => [
                        'type' => 'ajax'
                    ]
        ]);
    }

    public function run()
    {
        if ($this->edit) {
            return $this->renderEdit();
        } else {
            return $this->renderAdd();
        }
    }

}