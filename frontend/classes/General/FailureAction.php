<?php

namespace TaskForce\General;

class FailureAction extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_failure';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING 
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Отказаться';
    }
}
