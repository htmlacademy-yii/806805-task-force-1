<?php

namespace TaskForce\General;

class RefuseAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'refuse_action';
    }

    public static function verifyAccess(Task $availableActions)
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
