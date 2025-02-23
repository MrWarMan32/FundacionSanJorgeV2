<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;


class GenerateTherapySchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:therapy-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera horarios de citas para las tres terapias';

    /**
     * Execute the console command.
     */


     public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $therapies = [
            1 => 'fisica', // ID 1 es Terapia Física
            2 => 'lenguaje',  // ID 2 es Terapia de Lenguaje
            3 => 'hipoterapia'      // ID 3 es Hipoterapia
        ]; // Tipos de terapia
        $doctors = [6, 8, 7]; // ID de los doctores disponibles (ajusta según tus IDs de doctores)

        foreach ($therapies as $therapyId => $therapyName) {
            foreach ($doctors as $doctor) {
                // Generar horarios para cada terapia
                $this->createAppointmentSchedule($therapyId, $doctor);
            }
        }

        $this->info('Horarios de citas generados con éxito.');
    }

    private function createAppointmentSchedule($therapy, $doctor)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']; // Días de la semana
        $startTime = Carbon::createFromTime(8, 0, 0); // Hora de inicio a las 8:00 AM
        $endTime = Carbon::createFromTime(12, 0, 0); // Hora de fin a las 12:00 PM

        foreach ($days as $day) {
            $currentTime = $startTime->copy();

            // Crear citas cada 20 minutos hasta las 12 PM
            while ($currentTime->lte($endTime)) {
                Appointment::create([
                    'therapy_id' => $therapy,
                    'doctor_id' => $doctor,
                    'day' => $day,
                    'start_time' => $currentTime->format('H:i:s'),
                    'end_time' => $currentTime->addMinutes(20)->format('H:i:s'),
                    'available' => true,
                ]);
            }
        }
    }

    
}
