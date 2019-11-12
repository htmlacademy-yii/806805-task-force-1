<?php

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require('TaskProcess.php');

// ТЕСТИРОВАНИЕ - примеры данных

// Возможные варианты Заказчика и Исполнителя

$users = [];

$users[] = ['id' => 1, 'name' => 'Ser', 'id_role' => TaskProcess::ROLE_CUSTOMER, 'category_I' => '', 'category_II' => '', 'category_III' => '']; // Заказчик
//$users[] = ['id' => 2, 'name' => 'employer', 'id_role' => TaskProcess::ROLE_CUSTOMER, 'category_I' => '', 'category_II' => '', 'category_III' => '']; // Заказчик сторонний
//$users[] = ['id' => 3, 'name' => 'Mister', 'id_role' => TaskProcess::ROLE_CONTRACTOR, 'category_I' => 1, 'category_II' => '', 'category_III' => '']; // Пользователь
$users[] = ['id' => 4, 'name' => 'workman', 'id_role' => TaskProcess::ROLE_CONTRACTOR, 'category_I' => '', 'category_II' => 2, 'category_III' => 3]; // Сторонний пользователь

// Возможные варианты $task
$tasks = [];

// Новое
$tasks[] = ['id' => 1, 'id_customer' => '1', 'id_contractor' => '', 'id_status' => TaskProcess::STATUS_NEW,
    'end_life' => '2019-11-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; 

// Выполняется/В работе/На исполнении
$tasks[] = ['id' => 1, 'id_customer' => 1, 'id_contractor' => 3, 'id_status' => TaskProcess::STATUS_FAILED,
    'end_life' => '2019-12-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; 

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/classes/_tests2.php) !!!  Ассерты почитать надо. 
foreach($users as $k => $v) {
    $task_process = new TaskProcess($tasks[$k], $users[$k]);
    $task_status = $task_process->id_status;
    print('curr_status: ');
    print($task_status);

    print('<br> show_next_status: ');
    $next_status = $task_process->show_next_status(TaskProcess::ACTION_CANCEL);
    print_r($next_status);

    print('<br> show_acts <br>');
    $list_acts = $task_process->show_actions();
    print_r($list_acts);

    print('<br> show_statuses <br>');
    print_r($task_process->show_statuses());

    if ($task_process->is_end_life) {
        print('<br> Задание просрочено <br>');    
    }

    $next_status_life = $task_process->show_next_status_by_life();
    if($next_status_life) {
        print('next_status_life: ');
        print($next_status_life);
    } 

    print('<hr>');
}