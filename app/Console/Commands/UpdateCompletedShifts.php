<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shifts;
use Carbon\Carbon;

class UpdateCompletedShifts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-completed-shifts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el estado de las citas completadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        Shifts::where('end_time', '<=', $now)
            ->where('status', '!=', 'Completada')
            ->update(['status' => 'Completada']);

        $this->info('Citas completadas actualizadas correctamente.');
    }
}
