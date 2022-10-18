<?php

namespace app\assets;

use yii\web\AssetBundle;

class FancyBox3Asset extends AssetBundle
{

    public $sourcePath = '@bower/fancybox/dist';
    public $js = [];
    public $css = [];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();

        if (YII_DEBUG === true) {
            $this->js = ['jquery.fancybox.js'];
            $this->css = ['jquery.fancybox.css'];
        } else {
            
            $this->js = ['jquery.fancybox.min.js'];
            $this->css = ['jquery.fancybox.min.css'];
        }
    }

}
