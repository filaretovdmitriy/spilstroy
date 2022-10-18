<?php

namespace app\modules\icms\assets;

use yii\web\AssetBundle;

class ChartC3Asset extends AssetBundle
{

    public $sourcePath = '@icms/assets/c3';
    public $css = [
        'css/c3.min.css'
    ];
    public $js = [
        'js/c3.min.js',
        'js/d3.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
