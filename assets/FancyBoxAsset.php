<?php

namespace app\assets;

use yii\web\AssetBundle;

class FancyBoxAsset extends AssetBundle
{

    static $customStyles = false;
    public $sourcePath = '@app/assets/sources/fancybox';
    public $js = [];
    public $css = [];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function registerAssetFiles($view)
    {
        if (self::$customStyles) {
            $this->css[] = 'jquery.fancybox.icms.css';
        } else {
            $this->css[] = 'jquery.fancybox.css';
        }
        $this->js[] = 'jquery.fancybox' . (!YII_DEBUG ? '.pack' : '') . '.js';
        parent::registerAssetFiles($view);
    }

}
