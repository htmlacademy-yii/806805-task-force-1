<?php

require_once ('vendor/autoload.php');
use TaskForce\General\AvailableActions;
use TaskForce\General\AbstractAction;
use TaskForce\General\AcceptAction;
use TaskForce\General\AddTaskAction;
use TaskForce\General\CancelAction;
use TaskForce\General\CompleteAction;
use TaskForce\General\OfferAction;
use TaskForce\General\SendMessAction;
use TaskForce\General\SetContractorAction;

use TaskForce\Exs\AvailableNamesException;

/**
 * Отладочная функция, выводит print_r-ом входной параметр
 * @param $value
 */
function d($value){
    echo "<pre>"; print_r($value); echo "</pre>";
}

// ТЕСТИРОВАНИЕ - примеры данных

$classData = [];
// ($taskId, $taskName, $currentStatus, $endDate, $customerId, $contractorId)

$classData[] = [1, 'task1', AvailableActions::STATUS_RUNNING, '2019-12-11', 1, 2];
$classData[] = [2, 'task2', AvailableActions::STATUS_RUNNING, '2019-12-11', 3, 1];
$classData[] = [3, 'task3', AvailableActions::STATUS_CANCELED, '2019-12-11', 1, 3];
$classData[] = [4, 'task4', 'STATUS_EMPTY', '2019-12-11', 1, NULL];
$classData[] = [5, 'task25', AvailableActions::STATUS_NEW, '2019-12-11', 3, NULL];

// ТЕСТИРОВАНИЕ ВЫЗОВ ОБЪЕКТА - вручную для каждого изменения (http://localhost/_tests-m2t2v2.php) !!! 

foreach($classData as $key => $data) {

    [$taskId, $taskName, $currentStatus, $endDate, $customerId, $contractorId] = $data;

    print("Задание-$taskId <br> ");

    try {

        $AvailableActions = new AvailableActions($taskId, $taskName, $currentStatus, $endDate, $customerId, $contractorId);

        print("<br> show_current_status: ");
        echo $AvailableActions->getCurrentStatus();

        $u = 1; // переключатель id пользователя 0 - $customerId or 1 - $contractorId
        $userid = $u ? $contractorId : $customerId; 
        print("<br> show_role [$userid]: ");
        echo $roleInTask = $AvailableActions->checkRoleInTask($userid); 

        print('<br> <br> show Available Actions before:'); 
        d($goodActions = $AvailableActions->getAvailableActions($currentStatus, $roleInTask));

        $i = 1; // ключ массава с разрешенными действиями, если существует
        if (!empty($goodActions[$i])) {
            print("show Next Status by Actions[$i]: "); echo $AvailableActions->getNextStatus($goodActions[$i]); // Следующий статус от ACTION
            echo '<br> ';
        } else {echo "show Next Status by Actions[$i]: No Next Status";}
        
        print('<hr>');

    } catch (Throwable $ex) {
        echo "!!!ИСКЛЮЧЕНИЕ!!! " . $ex->getMessage() . "<hr>";
    }

}

print '<br> show Statuses: '; d($AvailableActions->getStatuses());
print 'show Actions: '; d($AvailableActions->getActions());
print 'show Roles: '; d($AvailableActions->getRoles());
