<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once ('vendor/autoload.php');

use TaskForce\Fixtures\Csv2SqlConveter___v2;

function printPre($value) {
    print "<pre>"; print_r($value); print "</pre>"; 
}

// все данные о том, где содержится входной файл, имя выходного файла и правила формирования дополнительных полей.
$csvFilesWithRules = [
    [
        'path2file' => '/data/categories.csv',
        'path2save' => 'schemas/csv2sql/categories.sql',
        'table_name' => 'categories',
        'value_map' => [
            'name' => 0,
            'symbol' => 1
        ]
    ],

    [
        'path2file' => '/data/actions.csv',
        'path2save' => 'schemas/csv2sql/actions.sql',
        'table_name' => 'actions',
        'value_map' => [
            'symbol' => 0,
            'name' => 1
        ]
    ],

    [
        'path2file' => '/data/roles.csv',
        'path2save' => 'schemas/csv2sql/roles.sql',
        'table_name' => 'roles',
        'value_map' => [
            'symbol' => 0,
            'name' => 1
        ]
    ],

    [
        'path2file' => '/data/statuses.csv',
        'path2save' => 'schemas/csv2sql/statuses.sql',
        'table_name' => 'statuses',
        'value_map' => [
            'symbol' => 0,
            'name' => 1
        ]
    ],

    [
        'path2file' => '/data/tasks.csv',
        'path2save' => 'schemas/csv2sql/tasks.sql',
        'table_name' => 'tasks',
        'value_map' => [
            'id_status' => 0,
            'id_location' => function () {
                return rand(1, 1008);
            },
            'id_customer' => 2,
            'add_time' => 3,
            'id_category' => 4,
            'description' => 5,
            'end_date' => 6,
            'name' => 7,
            'address' => 8,
            'price' => 9,
            'latitude' => 10,
            'longitude' => 11
        ]
    ],

    [
        'path2file' => '/data/task_files.csv',
        'path2save' => 'schemas/csv2sql/task_files.sql',
        'table_name' => 'task_files',
        'value_map' => [
            'id_task' => 0,
            'file' => 1
        ]
    ],

    [
        'path2file' => '/data/running_tasks.csv',
        'path2save' => 'schemas/csv2sql/running_tasks.sql',
        'table_name' => 'running_tasks',
        'value_map' => [
            'id_task' => 0,
            'id_contractor' => 1
        ]
    ],

    [
        'path2file' => '/data/users.csv',
        'path2save' => 'schemas/csv2sql/users.sql',
        'table_name' => 'users',
        'value_map' => [
            'email' => 0,
            'name' => 1,
            'password' => 2,
            'reg_time' => 3,
            'address' => 4,
            'birth_date' => 5,
            'about' => 6,
            'phone' => 7,
            'skype' => 8,
            'id_role' => 9,
            'id_location' => function () {
                return rand(1, 1008);
            },
            'activity_time' => 11
        ]
    ],

    [
        'path2file' => '/data/user_portfolio_images.csv',
        'path2save' => 'schemas/csv2sql/user_portfolio_images.sql',
        'table_name' => 'user_portfolio_images',
        'value_map' => [
            'id_user' => 0,
            'image' => 1
        ]
    ],

    [
        'path2file' => '/data/user_specializations.csv',
        'path2save' => 'schemas/csv2sql/user_specializations.sql',
        'table_name' => 'user_specializations',
        'value_map' => [
            'id_user' => 0,
            'id_category' => 1
        ]
    ],

    [
        'path2file' => '/data/user_notifications.csv',
        'path2save' => 'schemas/csv2sql/user_notifications.sql',
        'table_name' => 'user_notifications',
        'value_map' => [
            'symbol' => 0,
            'name' => 1
        ]
    ],

    [
        'path2file' => '/data/user_notification_settings.csv',
        'path2save' => 'schemas/csv2sql/user_notification_settings.sql',
        'table_name' => 'user_notification_settings',
        'value_map' => [
            'id_user' => 0,
            'id_notification' => 1,
            'on_off' => 2
        ]
    ],

    [
        'path2file' => '/data/favorite_users.csv',
        'path2save' => 'schemas/csv2sql/favorite_users.sql',
        'table_name' => 'favorite_users',
        'value_map' => [
            'id_user' => 0,
            'id_user_favorite' => 1,
            'on_off' => 2
        ]
    ],

    [
        'path2file' => '/data/feedbacks.csv',
        'path2save' => 'schemas/csv2sql/feedbacks.sql',
        'table_name' => 'feedbacks',
        'value_map' => [
            'id_user' => 0,
            'id_user_rated' => 1,
            'id_task' => 2,
            'add_time' => 3,
            'point' => 4,
            'desk' => 5
        ]
    ],

    [
        'path2file' => '/data/messages.csv',
        'path2save' => 'schemas/csv2sql/messages.sql',
        'table_name' => 'messages',
        'value_map' => [
            'id_task' => 0,
            'id_sender' => 1,
            'id_recipient' => 2,
            'mess' => 3,
            'add_time' => 4
        ]
    ],
    
    [
        'path2file' => '/data/offers.csv',
        'path2save' => 'schemas/csv2sql/offers.sql',
        'table_name' => 'offers',
        'value_map' => [
            'id_task' => 0,
            'id_contractor' => 1,
            'desk' => 2
        ]
    ]

];

foreach($csvFilesWithRules as $file){

    // получаем sql
    // это всего лишь прототип, можно еще под себя переделать внутренность метода.
    $sql = Csv2SqlConveter___v2::getSqlFromCsv($file['path2file'], $file['value_map'], $file['table_name'], __DIR__);
    printPre($sql);
    // sql получен, нужно просто сохранить исопльзуя file_put_contest например
    // ! важно, это сохранение можно реализовать и в этом же классе.
    // сохраняет в первый раз, в следующих проходах идет перезапись
    $saver = file_put_contents($file['path2save'], $sql);
    if($saver) {echo 'fire';}
}
