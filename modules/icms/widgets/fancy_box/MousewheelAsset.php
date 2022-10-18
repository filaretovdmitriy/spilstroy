<?php

namespace app\modules\icms\widgets\fancy_box;

use yii\web\AssetBundle;

class MousewheelAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/jquery-mousewheel';
    public $js = [];
    public $css = [];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\FancyBoxAsset',
    ];

    public function registerAssetFiles($view)
    {
        $this->js[] = 'jquery.mousewheel' . (!YII_DEBUG ? '.min' : '') . '.js';
        parent::registerAssetFiles($view);
    }

}
