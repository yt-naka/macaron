<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Player; //追加
use \App\History;

class WriteLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:change_reverse_tax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change reverse tax';

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
        $governments = Player::where('role', 'government')->get();
        $merchants = Player::where('role', 'merchant')->get();

        foreach ($governments as $government){

            $delta_gov_money = 0;
            $merchants_gov = $merchants->where('government_id', $government->id);

            foreach($merchants_gov as $merchant_gov){
                $delta_merchant_money = $merchant_gov->money * $government->tax/100;

                $delta_gov_money += $delta_merchant_money;
                $merchant_tax = mt_rand(0, 25);

                $merchant_gov->fill([
                    'money' => $merchant_gov->money * (1 - $government->tax/100),
                    'tax' => $merchant_tax,
                ])->save();
                History::create([
                    'player_id' => $merchant_gov->id,
                    'log' => 'updated money and Tb ( -δ'.floor($delta_merchant_money).', '.$merchant_tax.'% )',
                ]);
            }

            $government->fill([
                'money' => $government->money + $delta_gov_money,
            ])->save();
            History::create([
                'player_id' => $government->id,
                'log' => 'updated money ( +δ'.floor($delta_gov_money).' )',
            ]);

        }

        
        
        logger()->info('Change Reverse-Tax And Run Capital-Tax');
    }
}
