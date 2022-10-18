<?php

namespace app\modules\icms\components;

class Request extends \yii\web\Request
{

    public function init()
    {
        $baseUrl = $this->getBaseUrl();
        if ($baseUrl === '/icms') {
            $this->setBaseUrl('');
        }
        parent::init();
    }

}
