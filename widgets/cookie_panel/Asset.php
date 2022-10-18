<?php

namespace app\widgets\cookie_panel;

use app\components\AssetBundle;
use yii\web\JqueryAsset;

class Asset extends AssetBundle
{

    public $sourcePath = '@app/widgets/cookie_panel/assets';

    public $css = [
        'styles.css',
    ];
    public $js = [
        'scripts.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];

}
