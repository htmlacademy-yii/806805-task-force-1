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
        if (Task::STATUS_NEW && Task::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Выбрать исполнителя';
    }
}
