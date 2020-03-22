<?php

namespace NeubusSrm\Listeners;

use NeubusSrm\Events\UserWasAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use NeubusSrm\Models\Auth\User;

/**
 * Class UserWasAddedListener
 * @package NeubusSrm\Listeners
 */
class UserWasAddedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserWasAdded  $event
     * @return void
     */
    public function handle(UserWasAdded $event) : void
    {
        $this->setUserRequiredAttributes($event->getNewUser());
    }

    /**
     * @param User $user
     */
    public function setUserRequiredAttributes(User $user) : void {

        // first hook, see if the user has mfa required based on their role
        $this->requireMfa($user);

    }

    /**
     * @param User $user
     */
    public function requireMfa(User $user) : void {

        // this config option determines who does and doesn't have mfa required on account creation
        $user->has_mfa = config("srm.requires_mfa.{$user->role}");

        $user->save();
    }
}
