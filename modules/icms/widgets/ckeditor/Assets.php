<?php

namespace app\modules\icms\widgets\ckeditor;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/ckeditor';
    public $js = [
        'ckeditor.js',
        'js.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
