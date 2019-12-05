<?php

namespace TaskForce\General;

class CancelAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_cancel';
    }

    public static function verifyAccess(AvailableActions $availableActions): bool
    {
        if (AvailableActions::STATUS_NEW && AvailableActions::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
       return 'Отменить';
    }
}
