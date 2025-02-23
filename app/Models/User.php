<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Address;
use App\Models\Therapy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
        'canton',
        'parish',
        'site',
        'street_1',
        'street_2',
        'reference',
        'email_verified_at',
        'therapy_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // Relación con la tabla 'therapies'
    public function therapies()
    {
        return $this->belongsTo(Therapy::class,'doctor_therapy', 'doctor_id', 'therapy_id');
    }

}
