<?php

namespace TaskForce\General;

class AcceptAction extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_accept';
    }

    public static function verifyAccess(AvailableActions $availableActions, int $userId): bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING 
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Добавить задание';
    }
}
