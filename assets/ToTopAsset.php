<?php

namespace app\assets;

use yii\web\AssetBundle;

class ToTopAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/to_top';
    public $js = [
        'js/UIToTop.js',
    ];
    public $css = [
        'css/UIToTop.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        if (YII_DEBUG && !\Yii::$app->request->isPjax) {
            $this->publishOptions['forceCopy'] = true;
        }

        \Yii::$app->view->registerJs("$().UItoTop({easingType: 'easeOutQuart', text: '↑<div class=\"toTopText\">наверх</div>'});");
    }

}
