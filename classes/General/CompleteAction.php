<?php

namespace TaskForce\General;

class CompleteAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_complete';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId) : bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING 
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Завершить задание';
    }
}
