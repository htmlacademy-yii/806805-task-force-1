<?php

require_once ('vendor/autoload.php');
use TaskForce\General\Task;
use TaskForce\General\AvailableActions;
use TaskForce\General\AddTaskAction;
use TaskForce\General\CancelAction;
use TaskForce\General\FinishAction;
use TaskForce\General\RefuseAction;
use TaskForce\General\RespondAction;
use TaskForce\General\SendMessageAction;
use TaskForce\General\SetExecutorAction;

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

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/_tests-M2T1.php) !!! 
foreach($users as $key => $user) {

    $task = new Task($user[0], $user[1], $user[2], $user[3], $user[4], $user[5]);

    print('<br> show_current_status: ');
    echo $task->getCurrentStatus();
    
    // Запустить $task = new AvailableAction
    $availableActions = new AvailableActions($user[0], $user[1], $user[2], $user[3], $user[4], $user[5]);

    print('<br> show Available Actions before');
    // Запустить $task = new AvailableAction($task->getAvailableActions(2)
    d($availableActions->getAvailableActions($user[4]));

    // Наверное, пока не очень понимаю как может быть нужно 
    $actionInfo = new AddTaskAction;
    d($actionInfo->verifyAccess($availableActions));

    $actionInfo = new CancelAction;
    d($actionInfo->verifyAccess($availableActions));

    $actionInfo = new FinishAction;
    d($actionInfo->verifyAccess($availableActions));

    $actionInfo = new RefuseAction;
    d($actionInfo->verifyAccess($availableActions));

    $actionInfo = new RespondAction;
    d($actionInfo->verifyAccess($availableActions));

    $actionInfo = new SendMessageAction;
    d($actionInfo->verifyAccess($availableActions));

    $actionInfo = new SetExecutorAction;
    d($actionInfo->verifyAccess($availableActions));

    print('<hr>');
}