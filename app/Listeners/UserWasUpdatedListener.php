<?php

namespace NeubusSrm\Listeners;

use NeubusSrm\Events\UserWasUpdated;
use NeubusSrm\Models\Auth\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UserWasUpdatedListener
 * @package NeubusSrm\Listeners
 */
class UserWasUpdatedListener
{

    /**
     * Handle the event.
     *
     * @param  UserWasUpdated  $event
     * @return void
     */
    public function handle(UserWasUpdated $event)
    {
        $this->updateUserRequiredAttributes($event->getUpdatedUser());
    }

    /**
     * @param User $user
     */
    public function updateUserRequiredAttributes(User $user) : void {
        // first hook, see if the user has mfa required based on their role
        $this->requireMfa($user);
    }

    /**
     * @param User $user
     */
    public function requireMfa(User $user) : void {
        // for updates, this config option doesn't need to be saved as it'll be in line
        // don't fire this for logins. even though the user is updated, there's no reason at all to set this
        if (\Route::currentRouteName() !== 'login.do.verify') {
            $hasMfa = config("srm.requires_mfa.{$user->role}");
            if ($hasMfa) {
                $user->has_mfa = true;
            }
            else {
                $user->has_mfa = false;
            }
            $user->otp_secret = null;
            $user->otp_verified = false;
            $user->update();
        }
    }
}
