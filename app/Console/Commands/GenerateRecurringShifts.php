<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use App\Models\Shifts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        $newDate = Carbon::parse($shift->date)->addWeek();

        // Verificar si ya existe una cita recurrente para la siguiente semana
        $existingShift = Shifts::where('parent_shift_id', $shift->id)
            ->where('date', $newDate->toDateString())
            ->first();

        if (!$existingShift) {
            // Determinar la cita original
            $originalShift = $shift;

            if ($shift->is_modified) {
                if ($shift->is_emergency) {
                    // Si es emergencia, clonar la cita antes de la modificación
                    $originalShift = Shifts::find($shift->parent_shift_id);
                } else {
                    // Si no es emergencia, esta cita se convierte en la nueva base
                    $shift->parent_shift_id = null;
                    $shift->save();
                }
            }

            if ($originalShift) {
                $newShift = $originalShift->replicate();
                $newShift->parent_shift_id = $originalShift->id;
                $newShift->date = $newDate->toDateString();
                $newShift->is_modified = false;
                $newShift->is_emergency = false;

                $newShift->created_at = now();
                $newShift->updated_at = now();

                $newShift->save();
            } else {
                $this->warn("No se encontró la cita original para la clonación.");
            }
        } else {
            $this->info("Ya existe una cita recurrente para la siguiente semana: " . $newDate->toDateString());
        }
    }

}
