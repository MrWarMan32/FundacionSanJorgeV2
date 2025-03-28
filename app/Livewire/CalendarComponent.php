<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shifts;

class CalendarComponent extends Component
{
    public $events = [];

    public function mount()
    {

        $this->events = Shifts::with('therapy', 'doctor', 'appointment')
            ->where('status', 'Pendiente')
            ->get()
            ->map(function ($shift) {
                return [
                    'title' => $shift->patient->name,
                    'start' => $shift->date . ' ' .$shift->appointment->start_time,
                    'end'   => $shift->date . ' ' . $shift->appointment->end_time,
                    'backgroundColor' => '#4A90E2', // Color de fondo
                ];
            });
    }


    public function render()
    {
    // Pasar las citas a la vista
    return view('livewire.calendar-component', [
        ]);
    }

}
