<?php

namespace TaskForce\General;

class SendMessAction extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_send_mess';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING 
            && ($availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CONTRACTOR 
                || $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CUSTOMER)) {
            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Отправить сообщение';
    }
}
