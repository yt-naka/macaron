<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Player; //追加
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use \App\History;
use \App\User;

class MoneyLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $player = Player::where('id', 1)->first();
        $url = storage_path() . '/app/public/chart_datas/1.json'; //あとから

        $new_json = file_get_contents($url);
        $new_json = json_decode($new_json, true);
        array_push($new_json['time'], Carbon::now('Asia/Tokyo')->toDateTimeString());
        array_push($new_json['money'], $player->money);

        $json = fopen($url, 'w+b');
        fwrite($json, json_encode($new_json));
    }
}
