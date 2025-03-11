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
                    
                    // Asignamos las horas de inicio y fin de la cita al shift
                    $record->start_time = $appointment->start_time;
                    $record->end_time = $appointment->end_time;
                    $record->save();  // Guardamos el shift actualizado

                    // // Puedes usar dd() para depurar el flujo de ejecución
                    // dd('Shift actualizado y Appointment modificado', [
                    //     'shift_id' => $record->id,
                    //     'appointment_id' => $appointment->id,
                    // ]);
                }
            }
        });
    }
}
