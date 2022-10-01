<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Player; //追加
use App\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemporaryPasswordNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'], //名前は50文字以下
            'username' => ['required', 'string', 'regex:/^\w+$/', 'min:5', 'max:15','unique:users'], //a-z A-Z 0-9 ハイフン が使える
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], //548265のような6桁の数字
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $governments = Player::where('role', 'government')->get();
        if($governments->count() == 1){
            player::create([
                'id' => $user->id,
                'role' => 'merchant',
                'name' => $data['name'],
                'money' => 1000000,
                'tax' => mt_rand(0, 25),
                'government_id' => 1,
                'chain' => '0',
            ]);
        }
        else{
            player::create([
                'id' => $user->id,
                'role' => 'merchant',
                'name' => $data['name'],
                'money' => 1000000,
                'tax' => mt_rand(0, 25),
                'government_id' => 0,
                'chain' => '0',
            ]);
        }

        Mail::to($data['email'])->send(new TemporaryPasswordNotification($data));

       
        foreach($governments as $government){
            $government->fill([
                'money' => $government->money + 1000000/$governments->count(),
            ])->save();
        }

        /*Product::create([
            'owner_id' => $user->id,
            'price' => 1000,
            'place' => 'merchant',
        ]);*/
        
        return $user;
    }
}
