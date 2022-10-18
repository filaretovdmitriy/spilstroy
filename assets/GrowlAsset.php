<?php

namespace app\assets;

use yii\web\AssetBundle;

class GrowlAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/growl';
    public $css = [
        'css/jquery.growl.css',
    ];
    public $js = [
        'js/jquery.growl.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
