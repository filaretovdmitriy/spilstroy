<?php

namespace app\assets;

use yii\web\AssetBundle;

class GritterAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/gritter';
    public $css = [
        'css/jquery.gritter.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset'
    ];

    public function init()
    {
        parent::init();

        if (YII_DEBUG && !\Yii::$app->request->isPjax) {
            $this->publishOptions['forceCopy'] = true;
        }
        $gritter = YII_DEBUG ? 'jquery.gritter.js' : 'jquery.gritter.min.js';
        array_unshift($this->js, 'js/' . $gritter);
    }

}
