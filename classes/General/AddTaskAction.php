<?php

namespace TaskForce\General;

class AddTaskAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_add_task';
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
        return 'Добавить задание';
    }
}