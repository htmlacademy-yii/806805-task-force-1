<?php

namespace ownsite\actions;

class ActionComplete extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_complete';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getRoleOfUser($userId) === $availableActions::ROLE_CUSTOMER
            && $availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING) {

            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Завершить задание';
    }
}
