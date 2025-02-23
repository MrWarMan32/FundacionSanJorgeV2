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
    ];

    public function therapy()
    {
        return $this->belongsTo(Therapy::class,);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,);
    }
}
