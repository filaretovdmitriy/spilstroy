<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;

class Preloader extends Widget
{

    public $show = false;
    public $autoPjax = true;

    public function init()
    {

        if ($this->autoPjax) {
            $this->view->registerJs(<<<JS
if ($.pjax !== undefined) {            
    $('body').on('click', '.grid-action', function() {
        $('#preloader').show();
    });
    $('body').on('pjax:send', function() {
        $('#preloader').show();
    });
    $('body').on('pjax:complete', function () {
        $('#preloader').hide();
    });
}
JS
            );
        }
    }

    public function run()
    {
        if ($this->show) {
            $showText = 'display: block';
        } else {
            $showText = 'display: none';
        }
        $preloaderHtml = <<<HTML
<div id="preloader" style="{$showText}">
    <div id="preloader_1" class="preloader"></div>
    <div id="preloader_2" class="preloader"></div>
    <div id="preloader_3" class="preloader"></div>
    <div id="preloader_4" class="preloader"></div>
    <div id="preloader_5" class="preloader"></div>
    <div id="preloader_6" class="preloader"></div>
    <div id="preloader_7" class="preloader"></div>
    <div id="preloader_8" class="preloader"></div>
</div>
HTML;

        return $preloaderHtml;
    }

}
