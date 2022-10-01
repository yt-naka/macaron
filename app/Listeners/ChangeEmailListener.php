<?php

namespace App\Listeners;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\ChangeEmailEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangeMailNotification;
use Illuminate\Auth\Notifications\VerifyEmail;

class ChangeEmailListener extends VerifyEmail
{
    public function handle(ChangeEmailEvent $event)
    {
        //新メールアドレスにメール確認
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
            session()->flash('toast_success', __('A verification link has been sent to your email address'));
            return redirect('/home');
        }else{
            session()->flash('toast_error', __('Error'));
            return redirect('/account');
        }
        //旧メールアドレスに変更を通知
        Mail::to($event->old_email)->send(new ChangeMailNotification($event->user));
    }
}