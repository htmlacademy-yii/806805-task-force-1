<?php

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require('_permitted_actions.php');

// ТЕСТИРОВАНИЕ - примеры данных

// Возможные варианты Заказчика и Исполнителя
//$user = ['id' => 1, 'name' => 'Ser', 'category_I' => '', 'category_II' => '', 'category_III' => '']; // Заказчик
//$user = ['id' => 2, 'name' => 'employer', 'category_I' => '', 'category_II' => '', 'category_III' => '']; // Заказчик сторонний
$user = ['id' => 3, 'name' => 'Mister', 'category_I' => 1, 'category_II' => '', 'category_III' => '']; // Пользователь
//$user = ['id' => 4, 'name' => 'workman', 'category_I' => '', 'category_II' => 2, 'category_III' => 3]; // Сторонний пользователь

// Возможные варианты $task
$task = ['id' => 1, 'id_employer' => '1', 'id_workman' => '', 'status' => 'Новое', 
    'endtime' => '2019-11-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; // Новое

/*
$task = ['id' => 1, 'id_employer' => '1', 'id_workman' => 3, 'status' => 'Выполняется/В работе/На исполнении', 
    'endtime' => '2019-11-29 12:00:00', 'name' => 'Task_test', 'desc' => 'Thin end of the wedge']; // Выполняется/В работе/На исполнении
*/

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/classes/_tests.php) !!!  Ассерты почитать надо. 

$permitted_actions = new _permitted_actions($task, $user);

$is_task_actions = $permitted_actions->make_task_actions();

$task_status = $permitted_actions->task_status;

$task_actions = $permitted_actions->get_task_actions();

$task_status_new = $permitted_actions->make_new_task_status();

print_r($task_status);
print('<br>');
print_r($task_actions);
print('<br>');
print_r($task_status_new);
print('<br>');
print_r($task_status);

