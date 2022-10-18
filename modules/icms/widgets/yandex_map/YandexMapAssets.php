<?php

namespace app\modules\icms\widgets\yandex_map;

use yii\web\AssetBundle;

class YandexMapAssets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/yandexMap';
    public $css = [
        'css/yandexStyle.css'
    ];
    public $js = [
        'js/cross-control.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
