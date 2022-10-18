<?php

$config = [
    'id' => 'frontend',
    'name' => 'Yii CMS v3',
    'version' => '3.8.74',
    'basePath' => dirname(__DIR__),
    'language' => 'ru_RU',
    'aliases' => [
        '@backups' => '@app/backups',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'debug' => [
            'class' => 'app\components\debug\Module',
        ],
    ],
    'components' => [
        'formatter' => [
            'timeZone' => 'Europe/Moscow',
            'dateFormat' => 'd.MM.Y',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'd.MM.Y H:mm',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            //Прописывать во frontend.php и backend.php
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
//              Если не отправляются письма добавить
//             'transport' => [
//                 'class' => Swift_MailTransport::class,
//                 'extraParams' => null,
//             ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
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
    ],
];

//Генерируем ключ по названию сайта
$config['components']['request']['cookieValidationKey'] = md5('dUEgF7' . $config['id'] . $config['name'] . 'aisolNeIveoai8jIamS');
return $config;
