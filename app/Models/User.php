<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    
    use Notifiable;
    use HasApiTokens, Notifiable;


    protected $fillable = [
        'name','email','password','peran'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

   
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }
}
