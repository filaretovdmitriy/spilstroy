<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
if (YII_DEBUG) {
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    error_reporting(E_ALL);
    ini_set('display_errors', 'true');
} else {
    error_reporting(~E_ALL);
    ini_set('display_errors', 'false');
}

$redirects = require(__DIR__ . '/../config/redirects.php');
if (!empty($redirects)) {
    $path = isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    if (array_key_exists($path, $redirects)) {
        $queryString = (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
        $redirect = $redirects[$path];
        header('Location: ' . $redirect[0] . $queryString, true, $redirect[1]);
        exit;
    }
    unset($path);
}
unset($redirects);

if (is_null(filter_input(INPUT_GET, 'requirements')) === false && YII_DEBUG === true && isset($_SERVER['PATH_INFO']) === false) {
    require(__DIR__ . '/../requirements.php');
    exit;
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = yii\helpers\ArrayHelper::merge(
                require(__DIR__ . '/../config/web.php'), require(__DIR__ . '/../config/frontend.php')
);

(new yii\web\Application($config))->run();
