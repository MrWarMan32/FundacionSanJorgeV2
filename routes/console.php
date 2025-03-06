<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

 
// Registrar el comando de citas recurrentes viernes a las 12:30 pm
Artisan::command('generate:recurring-shifts', function () {
    $this->call(\App\Console\Commands\GenerateRecurringShifts::class);
});

// Registrar el comando de citas completadas, cada 20 min
Artisan::command('app:update-completed-shifts', function () {
    $this->call(\App\Console\Commands\UpdateCompletedShifts::class);
});
