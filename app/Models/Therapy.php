<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    protected $fillable = ['therapy_type', 'description', 'duration'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
