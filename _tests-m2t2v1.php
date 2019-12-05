<?php

require_once ('vendor/autoload.php');
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
// ($taskId, $taskName, $currentStatus, $endDate, $customerId, $contractorId)

$users[] = [1, 'task1', AvailableActions::STATUS_NEW, '2019-12-11', 1, 2];
$users[] = [2, 'task2', AvailableActions::STATUS_RUNNING, '2019-12-11', 1, 2];
$users[] = [3, 'task3', AvailableActions::STATUS_CANCELED, '2019-12-11', 1, 2];
$users[] = [4, 'task4', AvailableActions::STATUS_NEW, '2019-12-11', 3, NULL];
$users[] = [5, 'task25', AvailableActions::STATUS_RUNNING, '2019-12-11', 3, ''];

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/_tests-m2t2v1.php) !!! 
foreach($users as $key => $user) {

    $AvailableActions = new AvailableActions($user[0], $user[1], $user[2], $user[3], $user[4], $user[5]);

    print('<br> show_current_status: ');
    echo $AvailableActions->getCurrentStatus();

    print('<br> show Available Actions before');
    d($AvailableActions->getAvailableActions($user[4])); // $user[4] и $user[5] - это ИД заказчика и исполнителя

    print('<br> show Next Status: ');
    // Запустить $task = new AvailableAction($task->getAvailableActions(2)
    echo $AvailableActions->getNextStatus(AvailableActions::ACTION_SET_CONTRACTOR); // по действию находится следующий статус.

    print('<br> <br> show Available Actions after');
    d($AvailableActions->getAvailableActions($user[4])); // $user[4] и $user[5] - это ИД заказчика и исполнителя соотвественно

    print 'show Statuses: '; d($AvailableActions->getStatuses());
    print 'show Actions: '; d($AvailableActions->getActions());

    /*
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
*/
    print('<hr>');
}