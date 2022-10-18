<?php

namespace app\modules\icms\assets;

use yii\web\AssetBundle;

class PerfectScrollbarAsset extends AssetBundle
{

    public $sourcePath = '@icms/assets/perfect-scrollbar';
    public $js = [
        'js/perfect-scrollbar.jquery.js',
    ];
    public $css = [
        'css/perfect-scrollbar.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
