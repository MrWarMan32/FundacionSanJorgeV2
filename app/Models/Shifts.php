<?php

namespace App\Models;

use App\Console\Commands\GenerateRecurringShifts;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    protected $table = 'shifts';

    protected $fillable = [
        'is_recurring',
        'patient_id',
        'doctor_id',
        'therapy_id',
        'date',
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

    protected static function booted()
    {

        // Definimos el evento saved para ejecutar la lógica después de guardar el modelo
        static::saved(function ($record) {
            // Verificamos si appointment_id está presente en el shift
            if ($record->appointment_id) {
                $appointment = Appointment::find($record->appointment_id);
                
                // Verificamos si la cita fue encontrada y si está disponible
                if ($appointment && $appointment->available) {
                    // Actualizamos el estado de la cita y asignamos el paciente
                    $appointment->available = false;
                    $appointment->patient_id = $record->patient_id;
                    $appointment->save();  // Guardamos los cambios en la cita
                    
                }
            }
        });

        //evento para la eliminacion de citas 
        static::deleting(function (Shifts $shift) {
            // Buscar el horario en appointments usando el id almacenado en la cita
            $appointment = Appointment::find($shift->appointment_id);
    
            if ($appointment) {
                // Desasociar el paciente y marcar el horario como disponible
                $appointment->patient_id = null;
                $appointment->available = 1;
                $appointment->save();
            }
        });


        // //evento para la edicion de citas sin afectar la generacion semanal
        // static::saved(function ($shift) {
        //     // Si la cita es una emergencia, solo se actualiza la cita sin modificar las recurrentes
        //     if ($shift->is_emergency) {
        //         // Si es emergencia, no generamos citas recurrentes
        //         return;
        //     }

        //     // Si no es emergencia, generamos las citas recurrentes para el cambio
        //     (new GenerateRecurringShifts)->handle();
        // });



    }
}
