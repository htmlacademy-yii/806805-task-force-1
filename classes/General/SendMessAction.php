<?php

namespace TaskForce\General;

class SendMessAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_send_mess';
    }

    public static function verifyAccess(Task $availableActions)
    {
        if (Task::STATUS_RUNNING && (Task::ROLE_CUSTOMER || Task::ROLE_CONTRACTOR)) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Отправить сообщение';
    }
}
