<?php

namespace TaskForce\General;

class CancelAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'cancel_action';
    }

    public static function verifyAccess(Task $availableActions): bool
    {
        if (Task::STATUS_NEW && Task::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
       return 'Отменить задание';
    }
}
