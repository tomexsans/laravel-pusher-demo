<?php

namespace App\Listeners;

use App\Events\SayHiToAll;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSayHiToAll
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SayHiToAll $event): void
    {
        //
    }
}
