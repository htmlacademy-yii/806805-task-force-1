<?php

namespace ownsite\actions;

class ActionOffer extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_offer';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        // Добавить что он еще не откликался на это задание.
        if ($availableActions->getRoleOfUser($userId) === $availableActions::ROLE_CONTRACTOR
            && $availableActions->getCurrentStatus() === $availableActions::STATUS_NEW) {

            return true;
        }

        return false;
    }

    public static function getActionName(): string
    {
        return 'Откликнуться';
    }
}
