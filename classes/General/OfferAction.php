<?php

namespace TaskForce\General;

class OfferAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_offer';
    }

    public static function verifyAccess(AvailableActions $availableActions, $userId) : bool
    {
        if ($availableActions->getCurrentStatus() === $availableActions::STATUS_NEW 
            && $availableActions->checkRoleInTask($userId) === $availableActions::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Откликнуться';
    }
}
