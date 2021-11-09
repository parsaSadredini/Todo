<?php

namespace App\Console;

use App\Models\Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $tasks = Task::where("is_noticed",false)->get();
            foreach($tasks as $oneTask){

                $d1 = strtotime(now());
                $d2 = strtotime($oneTask->when_to_do);
                $totalSecondsDiff = abs($d1-$d2);
                $totalMinutesDiff = $totalSecondsDiff/60;
                echo $totalMinutesDiff;
                if($totalMinutesDiff < 10){
                    echo "the time has come";
                    $oneTask->is_noticed = true;
                    $oneTask->update();
                }

            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
