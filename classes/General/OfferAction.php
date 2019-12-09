<?php

namespace TaskForce\General;

class OfferAction extends AbstractAction
{
    public static function getActionSymbol(): string
    {
        return 'action_offer';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId): bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_NEW 
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName(): string
    {
        return 'Откликнуться';
    }
}
