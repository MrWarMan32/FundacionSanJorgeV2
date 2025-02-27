<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Registrar el comando
Artisan::command('generate:recurring-shifts', function () {
    $this->call(\App\Console\Commands\GenerateRecurringShifts::class);
});

// Programar el comando para que se ejecute semanalmente
Schedule::command('generate:recurring-shifts')->weekly();