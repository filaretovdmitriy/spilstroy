<?php

namespace app\modules\icms\assets;

use yii\web\AssetBundle;

class IcmsAsset extends AssetBundle
{

    public $sourcePath = '@icms/assets/main';
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'js/scripts.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\GritterAsset',
        'app\modules\icms\assets\PerfectScrollbarAsset',
    ];


    public static function path($relativePath = '')
    {
        $obj = new self();
        return \Yii::$app->assetManager->getPublishedUrl($obj->sourcePath) . '/' . $relativePath;
    }

    public function init()
    {
        parent::init();

        if (YII_DEBUG && !\Yii::$app->request->isPjax) {
            $this->publishOptions['forceCopy'] = true;
        }
        
        $this->addIe9Style();
    }

    public function addIe9Style()
    {
        $view = \Yii::$app->getView();
        $manager = $view->getAssetManager();
        $view->registerCssFile($manager->getAssetUrl($this, 'css/ie.css'), ['condition' => 'IE9']);
    }

}
