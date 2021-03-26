<?php

namespace ownsite\actions;

class ActionAddTask extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_add_task';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getRoleOfUser($userId) === $availableActions::ROLE_CUSTOMER
            && $availableActions->getCurrentStatus() === null) {

            return true;
        }

        return false;
    }

    public static function getActionName(): string
    {
        return 'Добавить задание';
    }
}
