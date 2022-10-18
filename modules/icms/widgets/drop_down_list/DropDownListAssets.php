<?php

namespace app\modules\icms\widgets\drop_down_list;

use yii\web\AssetBundle;

class DropDownListAssets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/select2';
    public $css = [
        'css/select2.min.css'
    ];
    public $js = [
        'js/select2.full.min.js',
        'js/i18n/ru.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
