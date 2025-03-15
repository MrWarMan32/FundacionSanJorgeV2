<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // // Programa la tarea para actualizar el estado de las citas completadas cada 20 minutos
        // $schedule->command('app:update-completed-shifts')->everyTwentyMinutes();

        
        // // Programar el comando para que se ejecute semanalmente viernes a las 12:30 pm
        // $schedule->command('generate:recurring-shifts')->fridays()->at('12:30');

    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}