<?php

namespace App\Console\Commands;

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
        $newShift = $shift->replicate(); // Clona la cita original
        $newShift->parent_shift_id = $shift->id; // Relaciona con la cita original
        $newShift->start_time = Carbon::parse($shift->start_time)->addWeek(); // Mueve la cita una semana adelante
        $newShift->end_time = Carbon::parse($shift->end_time)->addWeek();
        $newShift->created_at = now();
        $newShift->updated_at = now();
        $newShift->save();
    }

}
