<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicalRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);

Auth::routes(['login' => false, 'middleware' => 'throttle:5,1']);

Route::get('/home', fn () => redirect()->route('dashboard'));

/*
|--------------------------------------------------------------------------
| Shared dashboard area (admin, doctor, reception)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,doctor,reception'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);
    Route::get('patients/{patient}/documents', [PatientController::class, 'documents'])->name('patients.documents');
    Route::post('patients/{patient}/documents', [PatientController::class, 'storeDocument'])->name('patients.documents.store');
    Route::delete('patients/documents/{document}', [PatientController::class, 'destroyDocument'])->name('patients.documents.destroy');

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::get('appointments/{appointment}/clinical-record/create', [ClinicalRecordController::class, 'createFromAppointment'])->name('clinical-records.create-from-appointment');

    // Clinical / Treatment Records
    Route::resource('clinical-records', ClinicalRecordController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // My profile (non-admin)
    Route::get('profile', [StaffController::class, 'profile'])->name('staff.profile');
    Route::put('profile', [StaffController::class, 'updateProfile'])->name('staff.profile.update');

    /*
    |--------------------------------------------------------------------------
    | Admin + Reception
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,reception'])->group(function () {
        Route::get('sms/send', [SmsController::class, 'send'])->name('sms.send');
        Route::post('sms/send', [SmsController::class, 'store'])->name('sms.store');
        Route::get('sms/logs', [SmsController::class, 'logs'])->name('sms.logs');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin only
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // SMS templates & automation
        Route::get('sms/templates', [SmsController::class, 'templates'])->name('sms.templates');
        Route::put('sms/templates/{template}', [SmsController::class, 'updateTemplate'])->name('sms.templates.update');

        // Staff & Roles
        Route::resource('staff', StaffController::class);
        Route::post('staff/{user}/toggle', [StaffController::class, 'toggle'])->name('staff.toggle');

        // Services & Rooms
        Route::resource('services', ServiceController::class);
        Route::resource('rooms', RoomController::class);

        // Settings
        Route::get('settings/clinic', [SettingController::class, 'clinic'])->name('settings.clinic');
        Route::put('settings/clinic', [SettingController::class, 'updateClinic'])->name('settings.clinic.update');
        Route::get('settings/working-hours', [SettingController::class, 'workingHours'])->name('settings.working-hours');
        Route::put('settings/working-hours', [SettingController::class, 'updateWorkingHours'])->name('settings.working-hours.update');
        Route::get('settings/sms', [SettingController::class, 'sms'])->name('settings.sms');
        Route::put('settings/sms', [SettingController::class, 'updateSms'])->name('settings.sms.update');
    });
});
