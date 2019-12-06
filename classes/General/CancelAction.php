<?php

namespace TaskForce\General;

class CancelAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_cancel';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId) : bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_NEW 
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Отменить';
    }
}
