<?php

namespace ownsite\actions;

class ActionAssign extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_assign';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getRoleOfUser($userId) === $availableActions::ROLE_CUSTOMER
            && $availableActions->getCurrentStatus() === $availableActions::STATUS_NEW) {

            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Назначить';
    }
}
