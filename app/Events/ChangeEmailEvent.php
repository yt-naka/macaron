<?php

namespace App\Events;

use App\User;

class ChangeEmailEvent
{
    public $user;
    public $old_email;

    public function __construct(User $user, string $old_email)
    {
        $this->user = $user;
        $this->old_email = $old_email;
    }
}