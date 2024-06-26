<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
if (YII_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'true');
}
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

$config = yii\helpers\ArrayHelper::merge(
                require(__DIR__ . '/../../config/web.php'), require(__DIR__ . '/../../config/backend.php')
);

(new yii\web\Application($config))->run();
