<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['canton', 'parish', 'site', 'address'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
