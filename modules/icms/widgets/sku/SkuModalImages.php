<?php

namespace app\modules\icms\widgets\sku;

use yii\base\Widget;
use app\modules\icms\widgets\fancy_box\FancyBox;

class SkuModalImages extends Widget
{

    public $buttonTarget = '.sku-action-images';
    public $pjaxId = '';
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

    private function renderView()
    {
        $jsAfter = <<<JS
function() {
    $.pjax.reload('#{$this->pjaxId}');
}
JS;
        return FancyBox::widget([
                    'target' => $this->buttonTarget,
                    'afterLoad' => $this->_js,
                    'afterClose' => $jsAfter,
                    'config' => [
                        'type' => 'ajax',
                        'maxWidth' => 1000
                    ]
        ]);
    }

    public function run()
    {
        return $this->renderView();
    }

}
