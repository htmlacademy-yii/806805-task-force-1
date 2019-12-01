<?php

namespace TaskForce\General;

abstract class AbstractAction
{
    abstract public static function getActionId();

    abstract public static function verifyAccess(Task $availableActions);

    abstract public static function getActionName();
}
