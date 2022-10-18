<?php

namespace app\modules\icms\widgets\fancy_box;

use yii\web\AssetBundle;

class FancyBoxHelpersAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/fancybox';
    public $js = [
        'helpers/jquery.fancybox-buttons.js',
        'helpers/jquery.fancybox-media.js',
        'helpers/jquery.fancybox-thumbs.js'
    ];
    public $css = [
        'helpers/jquery.fancybox-buttons.css',
        'helpers/jquery.fancybox-thumbs.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\FancyBoxAsset',
    ];

}
