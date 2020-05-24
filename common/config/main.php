<?php
return [
    'language' => 'ru-RU', // Язык приложения, код языка ru код страны RU Россия
    'timeZone' => 'Europe/Moscow', // Временная зона добавляет +3 часа
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'defaultRoute' => 'start', // Переопределяем Контроллер по умолчанию для всего приложения
    // 'layout' => 'start', // Шаблон изменение для всех контроллеров, оставлено main для всех как по умолчанию, для контроллера Start задано свойство $layout = start
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
