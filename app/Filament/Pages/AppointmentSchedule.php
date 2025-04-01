<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Resources\Pages\Page;

class AppointmentSchedule extends Page
{
    protected static string $resource = AppointmentResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells'; // Puedes elegir otro icono
    protected static string $view = 'filament.pages.appointment-schedule';
    protected static ?string $navigationLabel = 'Horario Escolar';


    public array $scheduleData = [];
    public array $daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
    public array $timeSlots = []; // Define tus intervalos de tiempo

    public function mount(): void
    {
        $this->generateTimeSlots();
        $this->loadScheduleData();
    }

    protected function generateTimeSlots(): void
    {
        $startTime = \Carbon\Carbon::createFromTime(8, 0, 0); // Ejemplo: 8:00 AM
        $endTime = \Carbon\Carbon::createFromTime(12, 0, 0);   // Ejemplo: 6:00 PM
        $interval = 20; // Intervalo en minutos

        while ($startTime <= $endTime) {
             $this->timeSlots[] = $startTime->format('H:i');
             $startTime->addMinutes($interval);
        }
    }

    protected function loadScheduleData(): void
    {
        $appointments = Appointment::with('doctor', 'therapy')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        foreach ($this->daysOfWeek as $day) {
            $this->scheduleData[$day] = [];
            foreach ($this->timeSlots as $timeSlot) {
                $this->scheduleData[$day][$timeSlot] = null; // Inicializar como vacío
            }
        }

        foreach ($appointments as $appointment) {
            $dayName = $appointment->day; // Asegúrate de que 'day' coincida con tus días
            $startTime = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');

            if (isset($this->scheduleData[$dayName]) && isset($this->scheduleData[$dayName][$startTime])) {
                // Manejar horarios que se superponen (podrías concatenar o usar otra lógica)
                $this->scheduleData[$dayName][$startTime] .= '<br>' . $appointment->doctor->name . ' (' . $appointment->therapy->therapy_type . ')';
            } else if (isset($this->scheduleData[$dayName]) && isset($this->scheduleData[$dayName][$startTime])) {
                $this->scheduleData[$dayName][$startTime] = $appointment->doctor->name . ' (' . $appointment->therapy->therapy_type . ')';
            }
        }
    }
}
