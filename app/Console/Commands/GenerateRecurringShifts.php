<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Filament\Notifications\Notification;
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
            // Si la cita es una emergencia, buscamos la cita original
            $originalShift = $shift->is_emergency ? Shifts::find($shift->parent_shift_id) : $shift;
           

             // Si se encuentra la cita original
            if ($originalShift) {
                // Replicamos la cita original para crear la nueva cita
                $newShift = $originalShift->replicate();

                // Establecemos el ID de la cita original como la cita principal
                $newShift->parent_shift_id = $originalShift->id;

                // Establecemos la fecha de la nueva cita como la próxima semana
                $newShift->date = $newDate->toDateString();

                // Desactivamos la opción de modificada
                $newShift->is_modified = false;

                // Marcamos que no es una emergencia
                $newShift->is_emergency = false;

                // Actualizamos las fechas de creación y actualización
                $newShift->created_at = now();
                $newShift->updated_at = now();

                // Guardamos la nueva cita
                $newShift->save();

                $this->info("Cita recurrente generada para la fecha: " . $newDate->toDateString());


                // Comprobamos si el lote anterior ha sido modificado
                $previousShifts = Shifts::where('parent_shift_id', $originalShift->id)
                ->orderBy('date', 'desc')
                ->take(2)
                ->get();

                // Si hay dos lotes sin modificaciones, actualizamos el parent_shift_id del próximo lote
                if ($previousShifts->count() == 2 && !$previousShifts->contains('is_modified', true)) {
                    $lastShift = $previousShifts->first();
                    $this->info("El lote anterior no tiene modificaciones, se convertirá en el original.");
                    Notification::make()
                        ->title('Citas Generadas')
                        ->body('El lote anterior no tiene modificaciones, se convertirá en el original.')  
                        ->success()
                        ->persistent()
                        ->send();
                    $newShift->parent_shift_id = $lastShift->id;
                    $newShift->save();
                }


            } else {
                $this->warn("No se encontró la cita original para la clonación.");
            }
        } else {
            $this->info("Ya existe una cita recurrente para la siguiente semana: " . $newDate->toDateString());
        }
    }

}
