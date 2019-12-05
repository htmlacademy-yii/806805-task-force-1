<?php

namespace TaskForce\General;

class CompleteAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_complete';
    }

    public static function verifyAccess(AvailableActions $availableActions)
    {
        if (Task::STATUS_RUNNING && Task::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Завершить задание';
    }
}
