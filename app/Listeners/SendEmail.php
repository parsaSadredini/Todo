<?php

namespace App\Listeners;

use App\Events\EmailTimeHasCome;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmail implements ShouldQueue
{

    public function __construct()
    {
        //
    }


    public function handle(EmailTimeHasCome $event)
    {

    }
}
