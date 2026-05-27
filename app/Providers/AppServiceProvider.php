<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword; 
use App\Models\Register;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

   public function boot(): void
    {
        ResetPassword::createUrlUsing(function (Register $user, string $token) {
            return 'http://localhost:8000/reset-password?token=' . $token . '&email=' . urlencode($user->email);
        });
    }
}
