<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    protected $fillable = ['therapy_type', 'description', 'duration'];

    public function doctors()
    {
        return $this->hasMany(User::class, 'doctor_therapy', 'therapy_id', 'doctor_id');
    }
}
