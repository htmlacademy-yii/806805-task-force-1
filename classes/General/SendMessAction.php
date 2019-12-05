<?php

namespace TaskForce\General;

class SendMessAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_send_mess';
    }

    public static function verifyAccess(AvailableActions $availableActions)
    {
        if (AvailableActions::STATUS_RUNNING && (AvailableActions::ROLE_CUSTOMER || AvailableActions::ROLE_CONTRACTOR)) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Отправить сообщение';
    }
}
