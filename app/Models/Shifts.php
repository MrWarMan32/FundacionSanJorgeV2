<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'is_recurring',
        'patient_id',
        'doctor_id',
        'therapy_id',
        'status',
        'notes',
        'appointment_id',
    ];

    // Relación con Paciente
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relación con Doctor
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Relación con Terapia
    public function therapy()
    {
        return $this->belongsTo(Therapy::class, 'therapy_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
