<?php

namespace App\Listeners;

use App\Events\MessageHasReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishMessage
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
     * @param  MessageHasReceived  $event
     * @return void
     */
    public function handle(MessageHasReceived $event)
    {
        //
    }
}
