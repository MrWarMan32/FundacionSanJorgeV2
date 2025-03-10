<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'users'; // Usa la tabla 'users'

    public function therapies()
    {
        return $this->belongsToMany(Therapy::class, 'doctor_therapies', 'doctor_id', 'therapy_id');
    }

    public function scopeDoctors($query)
    {
        return $query->where('status', 'doctor');
    }
}
