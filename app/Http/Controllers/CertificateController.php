<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shifts;
use App\Models\User;

class CertificateController extends Controller
{
    public function generateCertificate($id)
    {
        $shift = $this->getDataForPdf($id);

        $pdf = Pdf::loadView('certificates.certificate', ['shift' => $shift]);
       
        $patientName = str_replace(' ', '_', $shift['patient_name']);
        $patientLastName = str_replace(' ', '_', $shift['patient_last_name']);
        $patientId = $shift['patient_id']; 
        $fileName = "Certificado_{$patientName}_{$patientLastName}_{$patientId}.pdf";

        return $pdf->download($fileName);  
        // return $pdf->download('certificate.pdf');
    }

    protected function getDataForPdf($shiftId)
    {
        // Obtén los datos necesarios para el PDF
        $shift = Shifts::with(['patient', 'doctor', 'therapy','appointment'])->findOrFail($shiftId);


        return [
            'patient_id' => $shift->patient->id_card,
            'patient_name' => $shift->patient->name,
            'patient_last_name' => $shift->patient->last_name,
            'doctor_name' => $shift->doctor->name,
            'doctor_last_name' => $shift->doctor->last_name,
            'therapy_type' => $shift->therapy->therapy_type,
            'start_time' => $shift->appointment->start_time,
            'end_time' => $shift->appointment->end_time,
            'appointment_day' => $shift->appointment->day,
            'date' => $shift->date,
        ];
    }

    
    public function generateCertificate2($patientId)
    {
    // Obtener todas las citas del paciente
    $shift = Shifts::where('patient_id', $patientId)
                   ->with('appointment') // Cargar relación con appointments
                   ->get();

    // Obtener los datos del paciente
    $patient = User::find($patientId);

    // Preparar los datos para el certificado
    $data = [
        'patient' => $patient,
        'shifts' => $shift
    ];

    // Generar el PDF
    $pdf = PDF::loadView('certificates.certificate2', $data);

    // Descargar el certificado
    return $pdf->download('certificado_' . $patient->first_name . '_' . $patient->last_name . '.pdf');
    }
}
