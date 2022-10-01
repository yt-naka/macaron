<?php

use Illuminate\Database\Seeder;

use \App\User; //追加
use \App\Player;
use Illuminate\Support\Str;
use App\Mail\PasswordNotification;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password1 = 'aaa'; //テスト用
        $data1 = array('name' => 'admin', 'username' => 'admin', 'password1' => $password1);

        $user1 = User::create([
            'username' => $data1['username'],
            'email' => config('mail.username'),
            'email_verified_at' => Carbon::now('Asia/Tokyo'),
            'password' => Hash::make($data1['password1']),
        ]);
        Player::create([
            'id' => $user1->id,
            'role' => 'government',
            'name' => $data1['name'],
            'money' => 0,
            'tax' => 1,
            'government_id' => 0,
            'chain' => '0',
        ]);

        $password2 = 'bbb';
        $data2 = array('name' => 'maca', 'username' => 'maca', 'password2' => $password2);

        $user2 = User::create([
            'username' => $data2['username'],
            'email' => 'N.Y.h3201@icloud.com',
            'email_verified_at' => \Carbon\Carbon::now('Asia/Tokyo'),
            'password' => Hash::make($data2['password2']),
        ]);
        Player::create([
            'id' => $user2->id,
            'role' => 'merchant',
            'name' => $data2['name'],
            'money' => 0,
            'tax' => 1,
            'government_id' => 1,
            'chain' => '0',
        ]);

        //Mail::to(config('mail.username'))->send(new PasswordNotification($data));
    }

}
