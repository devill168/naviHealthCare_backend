<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Sanctum\HasApiTokens;

class Register extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens,Notifiable, CanResetPassword;

    protected $table = 'registers';

    protected $fillable = [
        'username',
        'gender',
        'email',
        'phone',
        'password',
        'role_id',
        'status',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function healthFacilityVisitors()
    {
        return $this->hasMany(HealthFacilityVisitor::class, 'user_id');
    }
    
   
}
