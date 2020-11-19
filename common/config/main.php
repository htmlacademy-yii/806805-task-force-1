<?php
return [
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'defaultRoute' => 'start/index',
    'layout' => 'main',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => '.',
        ],
        'errorHandler' => [
            'errorAction' => 'start/error',
        ],
        'user' => [
            'loginUrl' => ['/'],
            'identityClass' => 'frontend\models\db\Users',
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
                [
                    'pattern' => '<controller:(task|user)>/view/<ID>',
                    'route' => '<controller>s/view',
                ],
                [
                    'pattern' => 'users/<sorting>',
                    'route' => 'users/index',
                ],
                [
                    'pattern' => 'tasks/<category>',
                    'route' => 'tasks/index',
                ],
                'signup' => 'signup/index',
            ],
        ],
    ],
    'params' => [
        'defaultAvatarAddr' => 'img/man-glasses.jpg',
    ],
];
