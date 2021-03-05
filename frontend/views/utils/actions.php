<?php

use ownsite\actions\AvailableActions;

$this->title = 'Тестирование AvailableActions';


function varprnt($varName, $varValue = null){
    echo "$varName $varValue<br>";
}
$actionData = [];
$actionData[] = [null, 1, null];
$actionData[] = [AvailableActions::STATUS_NEW, 1, null];
$actionData[] = [AvailableActions::STATUS_CANCELED, 1, null];
$actionData[] = [AvailableActions::STATUS_RUNNING, 1, 2];
$actionData[] = [AvailableActions::STATUS_COMPLETED, 1, 2];
$actionData[] = [AvailableActions::STATUS_FAILED, 1, 2];

$userIDs = [1,2,3];

echo "<div>";
foreach($userIDs as $userID) {
    echo "<div style='border-bottom: 2px solid #$userID$userID$userID'> <br />";
        foreach($actionData as $key => [$currentStatus, $customerId, $contractorId]) {
            print('<p>');
            $AvailableActions = new AvailableActions($currentStatus, $customerId, $contractorId);

            varprnt("USER - $userID, Заказчик - $customerId, Исполнитель", $contractorId);

            $role = $AvailableActions->getRoleOfUser($userID);
            varprnt('Роль пользователя', $role ? "<b>$role</b>" : 'гость');

            varprnt('<br>Текущий статус', $currentStatus);
            $actions = $AvailableActions->getAvailableActions($userID);
            if (!$actions) {
                varprnt('Действия и статус не доступны');
            }
            foreach($actions as $action) {
                $nextStatus = $AvailableActions->getNextStatus($action);
                $nextStatuses = implode(', ', $nextStatus);
                varprnt("Дейстие - $action, следующий статус(ы)", $nextStatuses ? "<b>$nextStatuses</b>" : 'гость');
            }

            print('<br>-------------------------------------------------');
            print('</p>');
        }
    echo "</div>";
}
echo "</div>";
