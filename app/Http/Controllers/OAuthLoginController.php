<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Player;
use Socialite;
use Auth;

class OAuthLoginController extends Controller
{
    // Googleの認証ページへのリダイレクト処理
    public function getGoogleAuth($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Googleの認証情報からユーザー情報の取得
    public function authGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::firstOrNew(['email' => $googleUser->email]);

        if (!$user->exists) {

            
            $user['userid'] = 'aaaaa';
            $user['email'] = $googleUser->email; // Gmailアドレス
            $user['password'] = '0';

            $user->save();
            Player::create([
                'id' => 1,
                'role' => '0',
                'name' => $googleUser->getNickName() ?? $googleUser->getName() ?? $googleUser->getNick(),
                'money' => 0,
                'tax' => 0,
                'government_id' => 0,
                'have_players' => 0,
            ]);
        }

        Auth::login($user);
        return redirect()->route('home');
    }
}
