<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
    //return redirect('/admin/login');
});

Route::get('/certificates/{id}', [CertificateController::class, 'generateCertificate'])->name('certificates.generate');


Route::get('/generate-certificate/{patientId}', [CertificateController::class, 'generateCertificate2'])->name('certificates.generate2');


