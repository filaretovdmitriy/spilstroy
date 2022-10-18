<?php

$config = [
    'bootstrap' => ['debug'],
    'components' => [
        'user' => [
            'loginUrl' => ['account/login'],
        ],
        'urlManager' => [
            'class' => 'app\components\UrlManager',
            'rules' => [
                ['class' => 'app\components\url_rules\DefaultUrlRule'],
                [// Каталог
                    'routes' => [
                        '' => 'site/catalog',
                        '<catalog_categorie_alias:[\w-]+>' => 'site/catalog_categorie',
                        '<catalog_categorie_alias:[\w-]+>/<catalog_id:\d+>-<catalog_alias:[\w-]+>' => 'site/catalog_element',
                    ],
                ],
                [// Новости
                    'routes' => [
                        '' => 'site/news',
                        '<alias:[\w-]+>' => 'site/news_element',
                    ],
                ],
                [// Статьи
                    'routes' => [
                        '' => 'site/articles',
                        '<alias:[\w-]+>' => 'site/articles_element',
                    ],
                ],
                [// Галерея
                    'routes' => [
                        '' => 'site/gallery',
                        '<gallery_categorie_id:\d+>' => 'site/gallery_element',
                    ],
                ],
                [// Галерея
                    'routes' => [
                        '' => 'account/account_history',
                        '<order_id:\d+>' => 'account/account_history_order',
                    ],
                ],
            ]
        ],
        'view' => [
            'class' => 'app\components\View',

            'minifyOutput' => true,
            'minifyPath' => '@webroot/assets/minify',
            'forceCharset' => 'UTF-8',
            'compressOptions' => ['extra' => true, 'no-comments' => true],

        ],
    ],
];

if (YII_ENV === 'dev') {
    $config['components']['view']['enableMinify'] = false;
    $config['components']['assetManager']['appendTimestamp'] = true;
}

return $config;
