<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; //追加
use \App\User;
use \App\Player;
use App\Events\ChangeEmailEvent;
use Illuminate\Support\Facades\Validator;


class AccountController extends Controller
{
    public function index()
    {
        return view('account');
    }

    public function updateFace(Request $request)
    {
        $request->face->storeAs('public/face_list', Auth::id().'.jpg');
        return redirect('/home');
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);
        $request->user()->player->fill([
            'name' => $request->name,
        ])->save();
        session()->flash('toast_success', __('Changed name'));
        return redirect('/home');
    }

    public function updateUsername(Request $request)
    {

        $request->user()->fill([
            'username' => $request->username,
        ])->save();
        session()->flash('toast_success', __('Changed username'));
        return redirect('/home');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([ //バリデーションでemailを設定してるけど、判定がいまいち
            'email' => 'required|string|email|max:255',
        ]);
        $user = $request->user();
        if($user->email != $request->email && filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $old_email = $user->email;
            $user->forceFill([
                'email' => $request->email,
                'email_verified_at' => NULL,
            ])->save();
            event(new ChangeEmailEvent($user, $old_email));
        }else{
            session()->flash('toast_error', __('Error'));
            return redirect('/account');
        }
    }

    public function delete()
    {
        Auth::user()->delete();
        Auth::user()->player->delete();
        return redirect('/home');
    }

}
