<?php

namespace TaskForce\General;

class SetExecutorAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'set_executor';
    }

    public static function verifyAccess(Task $availableActions)
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
