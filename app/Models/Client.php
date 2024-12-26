<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Client extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'password'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
