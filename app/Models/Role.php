<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
     protected $fillable = [
        'name',
        'description',
    ];

    public function registers()
    {
        return $this->hasMany(Register::class, 'role_id');
    }
}
