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
        if (AvailableActions::STATUS_RUNNING && AvailableActions::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Завершить задание';
    }
}
