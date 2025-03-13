<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use App\Models\Shifts;
use Carbon\Carbon;

class GenerateRecurringShifts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:recurring-shifts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera citas recurrentes cada semana para los pacientes';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shifts = Shifts::where('is_recurring', true)->get();

        foreach ($shifts as $shift) {
            $this->createRecurringShift($shift);
        }

        $this->info('Citas recurrentes generadas con éxito.');
    }

    public function createRecurringShift($shift)
    {
        // Clonar la cita original
        $newShift = $shift->replicate();
        
        // Relacionar con la cita original
        $newShift->parent_shift_id = $shift->id;

        // Calcular la nueva fecha (una semana después)
        $newDate = Carbon::parse($shift->date)->addWeek(); // Añadir una semana a la fecha original 

        // Verificar si ese día tiene disponibilidad
        $availableAppointments = Appointment::where('day', $newDate->locale('es')->isoFormat('dddd'))
            ->where('available', 1)
            ->where('doctor_id', $shift->doctor_id)
            ->where('therapy_id', $shift->therapy_id)
            ->first();

        if ($availableAppointments) {
            // Si hay disponibilidad, asignar la nueva fecha y hora
            $newShift->date = $newDate->toDateString();

            $newShift->start_time = $shift->start_time;
            $newShift->end_time = $shift->end_time;

            $newShift->created_at = now();
            $newShift->updated_at = now();

            // Guardar la nueva cita
            $newShift->save();
            
        } else {
            $this->warn("No hay disponibilidad para el día " . $newDate->toDateString() . " para la terapia y doctor especificados.");
        }
        // $newShift = $shift->replicate(); // Clona la cita original
        // $newShift->parent_shift_id = $shift->id; // Relaciona con la cita original
        // $newShift->start_time = Carbon::parse($shift->start_time)->addWeek(); // Mueve la cita una semana adelante
        // $newShift->end_time = Carbon::parse($shift->end_time)->addWeek();
        // $newShift->created_at = now();
        // $newShift->updated_at = now();
        // $newShift->save();
    }

}
