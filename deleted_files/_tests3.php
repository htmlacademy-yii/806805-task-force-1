<?php

//date_default_timezone_set("Europe/Moscow");
//setlocale(LC_ALL, 'ru_RU');

require_once ('vendor/autoload.php');

use TaskForce\General\Task;

/**
 * Отладочная функция, выводит print_r-ом входной параметр
 * @param $value
 */
function d($value){
    echo "<pre>"; print_r($value); echo "</pre>";
}

// ТЕСТИРОВАНИЕ - примеры данных

$users = [];

$users[] = [1, 'Ivan', Task::STATUS_NEW, '2019-15-11', 1, 2];
$users[] = [2, 'Ivan', Task::STATUS_RUNNING, '2019-15-11', 1, 2];

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/_tests3.php) !!! 
foreach($users as $key => $user) {

    $task = new Task($user[0], $user[1], $user[2], $user[3], $user[4], $user[5]);

    print('<br> show_current_status: ');
    echo $task->getCurrentStatus();

    print('<br> show Available Actions before');
    d($task->getAvailableActions($user[4])); // $user[4] и $user[5] - это ИД заказчика и исполнителя

    print('show_next_status: ');
    echo $task->getNextStatus(Task::ACTION_SET_CONTRACTOR, $user[4]); // по действию находится следующий статус. $user[4] должно совпадать с getAvailableActions ниже

    print('<br> <br> show Available Actions after');
    d($task->getAvailableActions($user[4])); // $user[4] и $user[5] - это ИД заказчика и исполнителя

    print 'show Statuses: '; d($task->getStatuses());
    print 'show Actions: '; d($task->getActions());

    print('<hr>');
}