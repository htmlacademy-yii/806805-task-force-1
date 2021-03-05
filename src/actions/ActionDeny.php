<?php

namespace ownsite\actions;

class ActionDeny extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_deny';
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
        return 'Отказать';
    }
}
