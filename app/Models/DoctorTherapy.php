<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorTherapy extends Model
{
    use HasFactory;

    protected $table = 'doctor_therapies';

    protected $fillable = [
        'doctor_id',
        'therapy_id',
    ];

    public $timestamps = true;



    //RELACION TABLA TERAPIAS
    public function therapy()
    {
        return $this->belongsTo(Therapy::class,'therapy_id');
    }


    //RELACION TABLA USUARIOS
    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id');
    }
}
