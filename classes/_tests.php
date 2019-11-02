<?php

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require('_task_process.php');

// ТЕСТИРОВАНИЕ - примеры данных

// Возможные варианты Заказчика и Исполнителя
//$user = ['id' => 1, 'name' => 'Ser', 'id_role' => _task_process::ROLE_CUSTOMER, 'category_I' => '', 'category_II' => '', 'category_III' => '']; // Заказчик
//$user = ['id' => 2, 'name' => 'employer', 'id_role' => _task_process::ROLE_CUSTOMER, 'category_I' => '', 'category_II' => '', 'category_III' => '']; // Заказчик сторонний
$user = ['id' => 3, 'name' => 'Mister', 'id_role' => _task_process::ROLE_CONTRACTOR, 'category_I' => 1, 'category_II' => '', 'category_III' => '']; // Пользователь
//$user = ['id' => 4, 'name' => 'workman', 'id_role' => _task_process::ROLE_CONTRACTOR, 'category_I' => '', 'category_II' => 2, 'category_III' => 3]; // Сторонний пользователь

// Возможные варианты $task
/*
$task = ['id' => 1, 'id_customer' => '1', 'id_contractor' => '', 'id_status' => _task_process::STATUS_NEW, 
    'dt_end' => '2019-11-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; // Новое
*/

$task = ['id' => 1, 'id_customer' => 1, 'id_contractor' => 3, 'id_status' => _task_process::STATUS_NEW, 
    'dt_end' => '2019-11-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; // Выполняется/В работе/На исполнении

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/classes/_tests.php) !!!  Ассерты почитать надо. 

$task_process = new _task_process($task, $user);
$task_status = $task_process->id_task_status;
print($task_status);
print('<br>');
$id_next_status = $task_process->show_next_task_status();
print_r($id_next_status);
print('<br>');
$list_buttons = $task_process->list_task_buttons();
print_r($list_buttons);
print('<br>');
print_r($task_process->read_task_buttons());
