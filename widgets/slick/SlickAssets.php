<?php

namespace app\widgets\slick;

use yii\web\AssetBundle;

class SlickAssets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/slick';
    public $css = [
        'slick.css'
    ];
    public $js = [
        'slick.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
