<?php

namespace app\modules\icms\widgets\date_time_input;

use yii\web\AssetBundle;

class DateTimeAssets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/jquery.datetimepicker';
    public $css = [
        'css/jquery.datetimepicker.css'
    ];
    public $js = [
        'js/jquery.datetimepicker.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
