<?php

namespace NeubusSrm\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Events\UserWasAdded;
use NeubusSrm\Events\UserWasUpdated;
use NeubusSrm\Listeners\NeulogActionListener;
use NeubusSrm\Listeners\NeulogModelListener;
use NeubusSrm\Listeners\UserWasAddedListener;
use NeubusSrm\Listeners\UserWasUpdatedListener;

/**
 * Class EventServiceProvider
 * @package NeubusSrm\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserWasAdded::class => [
            UserWasAddedListener::class
        ],
        UserWasUpdated::class => [
            UserWasUpdatedListener::class
        ],
        NeulogModelEvent::class => [
            NeulogModelListener::class
        ],
        NeulogActionEvent::class => [
            NeulogActionListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        parent::boot();

        //
    }
}
