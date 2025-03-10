<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shifts;

class CertificateController extends Controller
{
    public function generateCertificate($id)
    {
        $shifts = $this->getDataForPdf($id);

        $pdf = Pdf::loadView('certificates.certificate', ['shift' => $shifts]);
        // ->setOptions([
        //     'defaultFont' => 'sans-serif',
        //     'isHtml5ParserEnabled' => true,
        //     'isRemoteEnabled' => true,
        //     'header-html' => view('certificates.encabezado')->render(), // Agregamos el encabezado
        // ]);
        return $pdf->download('certificate.pdf');
    }

    protected function getDataForPdf($shiftId)
    {
        // Obtén los datos necesarios para el PDF
        $shifts = Shifts::with(['patient', 'doctor', 'therapy'])->findOrFail($shiftId);

        return [
            'patient_name' => $shifts->patient->name,
            'doctor_name' => $shifts->doctor->name,
            'therapy_type' => $shifts->therapy->therapy_type,
            'start_time' => $shifts->start_time,
            'end_time' => $shifts->end_time,
        ];
    }
}
