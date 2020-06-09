<?php
return [
    'language' => 'ru-RU', 
    'timeZone' => 'Europe/Moscow', 
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'defaultRoute' => 'start', 
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '//' => '/',
                'users' => 'users/index',
                'users/<sorting>' => 'users/index',
                'tasks' => 'tasks/index',
                [
                    'pattern' => 'users/<sorting>',
                    'route' => 'users/index',
                    'defaults' => ['sorting' => ''],                
                ],
            ],
        ],
    ],
];
