<?php

namespace TaskForce\General;

class SetContractorAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_set_contractor';
    }

    public static function verifyAccess(AvailableActions $availableActions)
    {
        if (AvailableActions::STATUS_NEW && AvailableActions::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Выбрать исполнителя';
    }
}
