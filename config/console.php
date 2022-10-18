<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@web' => '/',
        '@webroot' => '@app/web', // Обязательно сменить при изменении WEB_ROOT сервера
        '@backups' => '@app/backups',

        '@image_cache' => '@webroot/resize/cache',
        '@image_cache/web' => '@web/resize/cache',

        '@upload' => '@webroot/upload/icms',
        '@upload/images' => '@upload/images',
        '@upload/files' => '@upload/files',

        '@upload/web' => '@web/upload/icms',
        '@images' => '@upload/web/images',
        '@files' => '@upload/web/files',

        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => yii\helpers\ArrayHelper::merge(
            require(__DIR__ . '/db.php'),
            require(__DIR__ . '/db-local.php')
        ),
    ]
];
