<?php

namespace App\Observers;

use App\Models\User;

use Uuid;
use Mail;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->guid = Uuid::generate()->string;
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //Mail::to($user->email)->queue(new WelcomeMail($user));
    }
}
