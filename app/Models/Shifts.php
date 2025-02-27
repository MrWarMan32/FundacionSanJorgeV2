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
        'parent_shift_id',
    ];
}
