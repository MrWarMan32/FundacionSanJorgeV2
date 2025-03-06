<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shifts;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function generateCertificate($id)
    {
        $shift = Shifts::findOrFail($id);

        if ($shift->status !== 'completed') {
            return redirect()->back()->with('error', 'La cita no está en estado completado.');
        }

        $pdf = PDF::loadView('certificates.certificate', compact('shift'));

        return $pdf->download('certificado_cita_' . $shift->id . '.pdf');
    }
}
