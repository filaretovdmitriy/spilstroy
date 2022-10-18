<?php

namespace app\modules\icms;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{

    public $controllerNamespace = 'app\modules\icms\controllers';

    public function bootstrap($app)
    {
        $fixWebRoot = Yii::getAlias('@webroot');
        $pos = strrpos($fixWebRoot, '/icms');
        if($pos !== false)    {
            $fixWebRoot = substr_replace($fixWebRoot, '', $pos, 5);
        }
        $pos = strrpos($fixWebRoot, '\icms');
        if($pos !== false)    {
            $fixWebRoot = substr_replace($fixWebRoot, '', $pos, 5);
        }
        Yii::setAlias('@webroot', $fixWebRoot);

        Yii::setAlias('@web', '/');

        Yii::setAlias('@upload', '@webroot/upload/icms');
        Yii::setAlias('@upload/images', '@upload/images');
        Yii::setAlias('@upload/files', '@upload/files');

        Yii::setAlias('@upload/web', '@web/upload/icms');
        Yii::setAlias('@images', '@upload/web/images');
        Yii::setAlias('@files', '@upload/web/files');

        Yii::setAlias('@image_cache', '@webroot/resize/cache');
        Yii::setAlias('@image_cache/web', '@web/resize/cache');

        Yii::setAlias('@icms', '@app/modules/icms');
        Yii::setAlias('@icms/views', '@icms/themes/views');
        Yii::setAlias('@icms/layouts', '@icms/views/layouts');

        Yii::setAlias('@icms/assets', '@icms/themes/assets');

        $this->setViewPath('@icms/themes/views');
    }

}
