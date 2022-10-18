<?php

namespace app\modules\icms\components;

class UrlManager extends \yii\web\UrlManager
{

    public function init()
    {
        $baseUrl = $this->getBaseUrl();
        if ($baseUrl === '/icms') {
            $this->setBaseUrl('/');
        }
        parent::init();
    }

}
