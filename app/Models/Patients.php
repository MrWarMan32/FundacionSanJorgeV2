<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'id_card',
        'gender',
        'birth_date',
        'age', 
        'ethnicity',
        'phone',
        'user_type',
        'status',
        'disability', 
        'id_card_status',
        'disability_grade',
        'diagnosis',
        'medical_history', 
        'address_id',
        'therapy_id',
    ];

}
