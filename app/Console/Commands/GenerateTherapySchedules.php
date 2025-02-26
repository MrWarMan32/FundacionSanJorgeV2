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
            1 => ['name' => 'fisica', 'doctor_id' => 29], // Terapia física
            2 => ['name' => 'lenguaje', 'doctor_id' => 30], // Terapia de lenguaje
            3 => ['name' => 'hipoterapia', 'doctor_id' => 31], // hipoterapia
        ];

        foreach ($therapies as $therapyId => $data) {
            $this->createAppointmentSchedule($therapyId, $data['doctor_id']);
        }

        $this->info('Horarios de citas generados con éxito.');
    }


    private function createAppointmentSchedule($therapyId, $doctorId)
    {
        $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']; // Días de la semana
        $startTime = Carbon::createFromTime(8, 0, 0); // Hora de inicio a las 8:00 AM
        $endTime = Carbon::createFromTime(12, 0, 0); // Hora de fin a las 12:00 PM

        foreach ($days as $day) {
            $currentTime = $startTime->copy();

            // Crear citas cada 20 minutos hasta las 12 PM
            while ($currentTime->addMinutes(20)->lte($endTime)) {
                $start = $currentTime->copy()->subMinutes(20)->format('H:i:s'); // Restauramos la hora de inicio correcta
                $end = $currentTime->format('H:i:s');

                $existingAppointment = Appointment::where([
                    'therapy_id' => $therapyId,
                    'doctor_id' => $doctorId,
                    'day' => $day,
                    'start_time' => $start,
                    'end_time' => $end,
                ])->exists();

                if (!$existingAppointment) {
                    // Crear solo si no existe
                    Appointment::create([
                        'therapy_id' => $therapyId,
                        'doctor_id' => $doctorId,
                        'day' => $day,
                        'start_time' => $start,
                        'end_time' => $end,
                        'available' => true,
                    ]);
                }
            }
        }
    }
}
