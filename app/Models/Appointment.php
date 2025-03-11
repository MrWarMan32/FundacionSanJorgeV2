<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'therapy_id',
        'day',
        'start_time',
        'end_time',
        'available',
        'patient_id'
    ];

    public function therapy()
    {
        return $this->belongsTo(Therapy::class,);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function patient()
    {
        return $this->hasOne(User::class, 'patient_id');
    }

    public function shift()
    {
       return $this->hasOne(Shifts::class, 'appointment_id');
    }
}
