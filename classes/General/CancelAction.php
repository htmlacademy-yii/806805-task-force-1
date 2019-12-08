<?php

namespace TaskForce\General;

class CancelAction extends AbstractAction
{
<<<<<<< Updated upstream
    public static function getActionId()
=======
    public static function getActionSymbol(): string
>>>>>>> Stashed changes
    {
        return 'cancel_action';
    }

<<<<<<< Updated upstream
    public static function verifyAccess(Task $availableActions): bool
=======
    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
>>>>>>> Stashed changes
    {
        if (Task::STATUS_NEW && Task::ROLE_CUSTOMER) {
            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
       return 'Отменить задание';
    }
}
