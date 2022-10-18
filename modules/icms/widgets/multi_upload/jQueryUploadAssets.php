<?php

namespace app\modules\icms\widgets\multi_upload;

use yii\web\AssetBundle;

class jQueryUploadAssets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/jquery.fileupload';
    public $css = [
        'css/jquery.fileupload.css'
    ];
    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
