<?php

namespace App\Providers;

use App\Events\DataDeletedEvent;
use App\Events\DataInsertedEvent;
use App\Events\DataUpdatedEvent;
use App\Events\ErrorEvent;
use App\Listeners\DataDeletedListener;
use App\Listeners\DataInsertedListener;
use App\Listeners\DataUpdatedListener;
use App\Listeners\ErrorListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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

        DataInsertedEvent::class => [
            DataInsertedListener::class,
        ],

        DataUpdatedEvent::class => [
            DataUpdatedListener::class,
        ],

        DataDeletedEvent::class => [
            DataDeletedListener::class,
        ],

        ErrorEvent::class => [
            ErrorListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
