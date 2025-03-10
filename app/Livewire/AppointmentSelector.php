<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Therapy;
use Livewire\Component;

class AppointmentSelector extends Component
{
    public $therapyId;
    public $doctorId;
    public $day;
    public $appointmentId;
    public $patientId; // Recibido desde el formulario principal

    public function mount($patientId)
    {
        $this->patientId = $patientId;
    }

    public function render()
    {
        $therapies = Therapy::all();
        $doctors = $this->therapyId ? Doctor::doctors()->whereHas('therapies', function ($query) {
            $query->where('therapy_id', $this->therapyId);
        })->get() : [];
        $days = $this->doctorId && $this->therapyId ? Appointment::where('doctor_id', $this->doctorId)->where('therapy_id', $this->therapyId)->pluck('day')->unique() : [];
        $appointments = $this->doctorId && $this->therapyId && $this->day ? Appointment::where('doctor_id', $this->doctorId)->where('therapy_id', $this->therapyId)->where('day', $this->day)->where('available', true)->get() : [];
        
        return view('livewire.appointment-selector', [
            'therapies' => $therapies,
            'doctors' => $doctors,
            'days' => $days,
            'appointments' => $appointments,
        ]);
    }

    public function selectAppointment()
    {
        $appointment = Appointment::find($this->appointmentId);
        if ($appointment) {
            $appointment->patient_id = $this->patientId;
            $appointment->available = false;
            $appointment->save();
        }

        $this->emit('appointmentSelected', $this->appointmentId);
        $this->dispatchBrowserEvent('close-modal');
    }
    
}
