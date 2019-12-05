<?php

namespace TaskForce\General;

class OfferAction extends AbstractAction
{
    public static function getActionSymbol()
    {
        return 'action_offer';
    }

    public static function verifyAccess(AvailableActions $availableActions)
    {
        if (AvailableActions::STATUS_NEW && AvailableActions::ROLE_CONTRACTOR) {
            return true;
        }
        return false;
    }

    public static function getActionName()
    {
        return 'Откликнуться';
    }
}
