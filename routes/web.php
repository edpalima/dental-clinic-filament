<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('homepage.index');
});

Route::get('/patient-registration', function () {
    return view('patient-registration.index');
})->name('register');;

Route::get('/consent-agreement', function () {
    return view('privacy-policy.index');
});

Route::get(
    '/appointments/{appointment}/download-pdf',
    [PDFController::class, 'downloadPDF']
)->name('appointments.download-pdf');

use App\Http\Controllers\ReportController;

Route::get('/appointment-reports/pdf', [ReportController::class, 'generateAppointmentReportPdf'])->name('appointment-reports.pdf');
