<?php

namespace app\assets;

use yii\web\AssetBundle;

class PopupAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/popup';
    public $js = [
        'js/popup.js',
    ];
    public $css = [
        'css/popup.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        if (YII_DEBUG && !\Yii::$app->request->isPjax) {
            $this->publishOptions['forceCopy'] = true;
        }
    }

}
