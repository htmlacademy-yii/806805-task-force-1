<?php

namespace ownsite\actions;

class ActionFailure extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_failure';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getRoleOfUser($userId) === $availableActions::ROLE_CONTRACTOR
            && $availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING) {

            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Отказаться';
    }
}
