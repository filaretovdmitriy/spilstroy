<?php

namespace app\components\controller;

use yii\web\Response;

/**
 * Контроллер ajax
 */
class Ajax extends \yii\web\Controller
{


    public function beforeAction($action)
    {
        if (\Yii::$app->request->isAjax === false) {
            throw new \yii\web\HttpException(403, 'Только для ajax');
        }
        
        \Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }
    
}
