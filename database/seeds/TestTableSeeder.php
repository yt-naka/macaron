<?php

use Illuminate\Database\Seeder;

use App\User; //追加
use App\Player;
use App\Product;
use Faker\Factory as Faker;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['merchant', 'government'];
        for($i = 1; $i <= 100; $i++ ) { 
            $faker = Faker::create();
            $user = User::create([
                'username' => Str::random(),
                'email' => Str::random(),
                'password' => Str::random(),
            ]);

            $role = $roles[array_rand($roles)];
            $is_merchant = ($role == 'merchant');
            Player::create([
                'id' => $user->id,
                'role' => $role,
                'name' => $faker->userName,
                'money' => $faker->numberBetween(0,1000000),
                'tax' => $is_merchant ? $faker->numberBetween(0, 20) : $faker->numberBetween(0, 5),
                'government_id' => $is_merchant ? Player::where('role', 'government')->inRandomOrder()->first()->id : 0,
                'chain' => '0',
            ]);

            if($is_merchant){
                Product::create([
                    'owner_id' => $user->id,
                    'price' => 1000,
                    'place' => 'merchant',
                ]);

            }
        }
        
    }
}
