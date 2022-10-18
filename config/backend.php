<?php

$config = [
    'bootstrap' => ['icms', 'debug'],
    'modules' => [
        'icms' => [
            'class' => 'app\modules\icms\Module',
        ]
    ],
    'components' => [
        'assetManager' => [
            'basePath' => '@webroot/icms/assets',
            'baseUrl' => '@web/icms/assets',
        ],
        'user' => [
            'loginUrl' => ['/icms'],
        ],
        'request' => [
            'class' => 'app\modules\icms\components\Request',
        ],
        'urlManager' => [
            'class' => 'app\modules\icms\components\UrlManager',
            'rules' => [
                // Лучше использовать стандартные правила вида <controller>/<action>
                'icms/dashboard' => 'icms/default/dashboard',
                'icms' => 'icms/default/index',
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'icms/default/error',
        ],
    ],
];
return $config;
