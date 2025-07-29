<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        // Llama a checkAndRefundInvestments mediante el controlador ProjectController diariamente.
        $schedule->call('App\Http\Controllers\ProjectController@checkAndRefundInvestments')->daily();
    }

}
