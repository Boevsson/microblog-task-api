<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'token'
    ];

    protected $hidden = [
        'password',
        'deleted_at'
    ];

    public function createToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->token = $token;
        $this->save();

        return $token;
    }
}