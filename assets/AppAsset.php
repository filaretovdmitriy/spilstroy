<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/app';
    
    public $css = [
        'css/reset.css',
        'css/style.css',
        'css/media.css',
        'plugins/slick/slick.css',
        'css/nouislider.css',
    

    ];
    public $js = [
        'plugins/slick/slick.min.js',
        'js/nouislider.js',
        'js/scripts.js',
    
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\ShopAsset',
        'app\assets\GrowlAsset',
        'app\assets\ToTopAsset',
        'app\assets\FancyBoxAsset',
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
        $publishUrl = $manager->getPublishedUrl($this->sourcePath);
        $view->registerJsFile($publishUrl . 'js/html5shiv.js', ['condition' => 'lt IE 9']);
        $view->registerJsFile($publishUrl . 'js/respond.min.js', ['condition' => 'lt IE 9']);
    }

}
