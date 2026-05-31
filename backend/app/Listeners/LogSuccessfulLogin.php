<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLogin;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        AuditLogin::create([
            'user_id' => $event->user->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }
}
