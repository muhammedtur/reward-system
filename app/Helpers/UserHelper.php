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
            $referralUser = User::where('guid', $invitation['created_user'])->first();

            if ($referralUser) {
                $user->referrer = $referralUser->id;
                $user->wallet = 30.00;
                $referralUser->increment('wallet', 50);
                Cache::forget("auth:invitationCode:{$invitationCode}");
            }
        }
    }
}
