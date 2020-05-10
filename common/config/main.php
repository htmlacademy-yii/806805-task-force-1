<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'defaultRoute' => 'start', // Переопределяем Контроллер по умолчанию для всего приложения
    // 'layout' => 'start', // Шаблон изменение для всех контроллеров, оставлено main для всех как по умолчанию, для контроллера Start задано свойство $layout = start
    // 'defaultAction' => 'index', // Не существует в основных настройках
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
                'tasks' => 'tasks/index',
            ],
        ],
    ],
];
