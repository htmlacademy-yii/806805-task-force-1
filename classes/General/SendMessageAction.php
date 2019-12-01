<?php

namespace TaskForce\General;

class SendMessageAction extends AbstractAction
{
    public static function getActionId()
    {
        return 'send_message';
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
