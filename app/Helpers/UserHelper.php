<?php

namespace App\Helpers;

use App\Models\User;
use Cache;

class UserHelper
{
    private function __construct()
    {
    }

    public static function checkInvitation($invitationCode, &$user)
    {
        $invitation = Cache::get("auth:invitationCode:{$invitationCode}");

        if ($invitation) {
            $referral_user = User::where('guid', $invitation['created_user'])->first();

            if ($referral_user) {
                $user->referrer = $referral_user->id;
                $user->wallet = 30.00;
                Cache::forget("auth:invitationCode:{$invitationCode}");
                return $user;
            }
        }
        return false;
    }
}
