<?php

namespace app\assets;

use yii\web\AssetBundle;

class ShopAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/shop';
    public $js = [
        'js/shop.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\GrowlAsset',
    ];

    public function init()
    {
        parent::init();

        if (YII_DEBUG && !\Yii::$app->request->isPjax) {
            $this->publishOptions['forceCopy'] = true;
        }
    }

}
