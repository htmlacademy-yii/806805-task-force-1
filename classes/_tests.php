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
// Новое
$task = ['id' => 1, 'id_customer' => '1', 'id_contractor' => '', 'id_status' => _task_process::STATUS_NEW, 
    'end_life' => '2019-11-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; 
*/
// Выполняется/В работе/На исполнении
$task = ['id' => 1, 'id_customer' => 1, 'id_contractor' => 3, 'id_status' => _task_process::STATUS_NEW, 
    'end_life' => '2019-12-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; 

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/classes/_tests.php) !!!  Ассерты почитать надо. 

$task_process = new _task_process($task, $user);
$task_status = $task_process->id_status;
print($task_status);

print('<br>');
$next_status = $task_process->show_next_status(_task_process::ACT_CANCEL);
print_r($next_status);

print('<br>');
$list_acts = $task_process->show_acts();
print_r($list_acts);

print('<br>');
print_r($task_process->show_statuses());

print('<br>');
$is_end_life = $task_process->check_is_end_life();
if($is_end_life) {print('Задание просрочено');}
