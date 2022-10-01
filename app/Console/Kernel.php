<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use \App\Player; //追加
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use \App\History;
use \App\User;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        
        \App\Console\Commands\WriteLog::class, //追加
        \App\Console\Commands\MoneyLog::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        /*$schedule
        ->command('commnad:name')
        ->daily();*/
        $schedule->call(function() {
            $player = Player::where('id', 1)->first();
            $url = storage_path() . '/app/public/chart_datas/1.json'; //あとから
    
            $new_json = file_get_contents($url);
            $new_json = json_decode($new_json, true);
            array_push($new_json['time'], Carbon::now('Asia/Tokyo')->toDateTimeString());
            array_push($new_json['money'], $player->money);
    
            $json = fopen($url, 'w+b');
            fwrite($json, json_encode($new_json));
        })->daily();
        $schedule //追加
        ->command('command:change_reverse_tax')
        ->daily();
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
