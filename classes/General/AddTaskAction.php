<?php

namespace TaskForce\General;

class AddTaskAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'add_task';
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
        return 'Добавить задание';
    }
}
