<?php

namespace TaskForce\General;

abstract class AbstractAction
{
    abstract public static function getActionSymbol();

    abstract public static function verifyAccess(AvailableActions $availableActions, $userId) : bool;

    abstract public static function getActionName();
}
