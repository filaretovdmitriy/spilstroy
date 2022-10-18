<?php

namespace app\commands;

class DefaultController extends \app\components\controller\Console
{

    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

}
