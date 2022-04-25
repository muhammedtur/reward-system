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
        // Checking invitation code
        $invitation = Cache::get("auth:invitationCode:{$invitationCode}");

        if ($invitation) {
            // Checking invitation user is exists
            $referral_user = User::where('guid', $invitation['created_user'])->first();

            if ($referral_user) {
                $user->referrer = $referral_user->id;
                $user->wallet = 30.00;
                $referral_user->increment('wallet', 50);
                Cache::forget("auth:invitationCode:{$invitationCode}");
            }
        }
    }
}
