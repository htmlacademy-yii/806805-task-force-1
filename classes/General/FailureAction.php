<?php

namespace TaskForce\General;

class FailureAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_failure';
    }

    public static function verifyAccess(AvailableActions $availableActions)
    {
        if (Task::STATUS_RUNNING && Task::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Отказаться';
    }
}
