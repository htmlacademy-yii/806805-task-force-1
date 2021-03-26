<?php
return [
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'defaultRoute' => 'site/index',
    'layout' => 'main',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => '.',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false, 
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                '/' => 'site/index',
                'utils' => 'utils/index',
                'tasks' => 'tasks/index',
                'users' => 'users/index',
                'signup' => 'signup/index',
                [
                    'pattern' => '<controller:(task|user)>/view/<ID>',
                    'route' => '<controller>s/view',
                ],
                [
                    'pattern' => 'tasks/<category>',
                    'route' => 'tasks/index',
                ],
            ],
        ],
    ],
    'params' => [
        'defaultAvatarAddr' => 'img/man-glasses.jpg',
    ],
];
