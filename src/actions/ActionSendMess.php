<?php

namespace ownsite\actions;

class ActionSendMess extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_send_mess';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        $role = $availableActions->getRoleOfUser($userId);
        if (($role === $availableActions::ROLE_CONTRACTOR || $role === $availableActions::ROLE_CUSTOMER)
            && $availableActions->getCurrentStatus() === $availableActions::STATUS_RUNNING) {

            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Отправить сообщение';
    }
}
