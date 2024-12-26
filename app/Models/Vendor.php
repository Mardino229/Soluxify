<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'password', 'kkiapay_id'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
