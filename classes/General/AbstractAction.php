<?php

namespace TaskForce\General;

abstract class AbstractAction
{
<<<<<<< Updated upstream
    abstract public static function getActionId();

    abstract public static function verifyAccess(Task $availableActions);
=======
    abstract public static function getActionSymbol(): string;

    abstract public static function verifyAccess(AvailableActions $availableActions, $userId): bool;
>>>>>>> Stashed changes

    abstract public static function getActionName(): string;
}
