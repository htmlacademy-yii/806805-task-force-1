<?php

namespace TaskForce\General;

class FinishAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'finish_action';
    }

    public static function verifyAccess(Task $availableActions)
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
