<?php

namespace TaskForce\General;

class AddTaskAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_add_task';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId) : bool
    {
        // ??? Не понимаю. Разве когда создается новое задание у него будет статус? почему === $availableActions::STATUS_NEW
        // ??? Может быть лучше написать событие отправки формы, например $_POST['add_task'] или $availableActions->getCurrentStatus() === NULL
        if ($_POST  
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Добавить задание';
    }
}
