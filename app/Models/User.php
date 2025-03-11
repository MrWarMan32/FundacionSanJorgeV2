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

    
    protected $guarded = []; //ELIMINAR DESPUES DE IMPORTAR SI LA VEZ ELIMINALAAAA

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */


      protected $casts = [
        'disability_type' => 'array',
       ];


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
        'disability_type',
        'disability_level',
        'representative_name',
        'representative_last_name',
        'representative_id_card',
        'id_card_status',
        'disability_grade',
        'diagnosis',
        'medical_history',
        'email_verified_at',
        'therapy_id',
        'id_address',
    ];


    //eliminar usuario y direccion asociada
    protected static function booted()
    {
        // Evento cuando se crea un usuario
        static::created(function ($user) {
            // Verificar si hay una dirección asignada al usuario
            if ($user->id_address) {
                // Asignar la dirección al usuario
                $address = Address::find($user->id_address);
                if ($address) {
                    $address->update(['user_id' => $user->id]);
                }
            }
        });

        static::deleting(function (User $user) {
            if ($user->id_address) {
                $addressId = $user->id_address;
    
                // Contar cuántos usuarios tienen la misma id_address
                $addressCount = User::where('id_address', $addressId)->count();
    
                if ($addressCount === 1) {
                    // Solo este usuario tiene la dirección, entonces la eliminamos
                    $address = Address::find($addressId);
                    if ($address) {
                        $address->delete();
                    }
                }
                // Eliminamos la relacion del usuario con la direccion.
                $user->id_address = null;
                $user->save();
            }
        });
    }


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


    /// Relación de un usuario (paciente) con las citas (shifts
    public function shifts()
    {
        return $this->hasMany(Shifts::class, 'patient_id');
    }

    // Relación de un usuario (doctor) con las citas (shifts)
    public function shiftsAsDoctor()
    {
        return $this->hasMany(Shifts::class, 'doctor_id');
    }


    // Relación con la tabla 'therapies'
    public function therapies()
    {
        return $this->belongsTo(Therapy::class,'doctor_therapy', 'doctor_id', 'therapy_id');
    }

    // Relación con la tabla 'appointments'
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    } 

    //relacion tabla addresses
    public function address()
    {
        return $this->hasMany(Address::class, 'id', 'id_address');
    }

}
