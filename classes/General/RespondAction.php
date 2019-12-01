<?php

namespace TaskForce\General;

class RespondAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'respond_action';
    }

    public static function verifyAccess(Task $availableActions)
    {
        if (Task::STATUS_NEW && Task::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Откликнуться';
    }
}
