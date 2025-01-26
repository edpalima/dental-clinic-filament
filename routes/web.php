<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

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

// Route::get('/', function () {
//     return view('homepage.index');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/patient-registration', function () {
    return view('patient-registration.index');
})->name('register');

Route::get('/consent-agreement', function () {
    return view('privacy-policy.index');
});

Route::get(
    '/appointments/{appointment}/download-pdf',
    [PDFController::class, 'downloadPDF']
)->name('appointments.download-pdf');

Route::get('/appointment-reports/pdf', [ReportController::class, 'generateAppointmentReportPdf'])->name('appointment-reports.pdf');

Route::post('/appointments/fetch', [AppointmentsController::class, 'fetch'])->name('appointments.fetch');

Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('email.notice');

Route::get('/admin/logout', [UserController::class, 'logout'])->name('filament.admin.auth.logout.new');
