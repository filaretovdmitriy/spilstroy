<?php

namespace app\widgets\back_call_popup;

use yii\web\AssetBundle;

class BackCallPopupAsset extends AssetBundle
{

    public $sourcePath = '@app/widgets/back_call_popup/assets';
    public $css = [
        'css/back-call-popup.css',
    ];
    public $depends = [
        'app\assets\PopupAsset',
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
