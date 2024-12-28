<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'market', 'password', 'kkiapay_id',
        'kkiapay_private_key', 'kkiapay_secret_key', 'kkiapay_public_key'
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
