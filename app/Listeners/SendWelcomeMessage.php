<?php

namespace App\Listeners;

use App\Events\AccountHasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeMessage
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
     * @param  AccountHasCreated  $event
     * @return void
     */
    public function handle(AccountHasCreated $event)
    {
        //
    }
}
