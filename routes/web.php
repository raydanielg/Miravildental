<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicalRecordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing page
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/book-appointment', [LandingController::class, 'bookAppointment'])->name('landing.appointment.book');
Route::get('/privacy-policy', [LandingController::class, 'privacy'])->name('landing.privacy');
Route::get('/terms-of-service', [LandingController::class, 'terms'])->name('landing.terms');
Route::get('/about-us', [LandingController::class, 'about'])->name('landing.about');
Route::get('/our-services', [LandingController::class, 'services'])->name('landing.services');
Route::get('/book-now', [LandingController::class, 'booking'])->name('landing.booking');
Route::get('/contact-us', [LandingController::class, 'contact'])->name('landing.contact');
Route::post('/subscribe-newsletter', [LandingController::class, 'subscribeNewsletter'])->name('landing.newsletter.subscribe');
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');
Route::get('/sitemap', [SitemapController::class, 'html'])->name('sitemap.html');
Route::get('/rss.xml', [SitemapController::class, 'rss'])->name('rss.feed');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Auth::routes(['login' => false, 'middleware' => 'throttle:5,1']);

Route::get('/home', fn () => redirect()->route('dashboard'));

/*
|--------------------------------------------------------------------------
| Shared dashboard area (admin, doctor, reception, customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,doctor,reception,customer'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);
    Route::get('patients/{patient}/edit-form', [PatientController::class, 'editForm'])->name('patients.edit-form');
    Route::get('patients/{patient}/documents', [PatientController::class, 'documents'])->name('patients.documents');
    Route::post('patients/{patient}/documents', [PatientController::class, 'storeDocument'])->name('patients.documents.store');
    Route::delete('patients/documents/{document}', [PatientController::class, 'destroyDocument'])->name('patients.documents.destroy');

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::get('appointments/{appointment}/edit-form', [AppointmentController::class, 'editForm'])->name('appointments.edit-form');
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::get('appointments/online/list', [AppointmentController::class, 'online'])->name('appointments.online');
    Route::post('appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
    Route::get('appointments/{appointment}/clinical-record/create', [ClinicalRecordController::class, 'createFromAppointment'])->name('clinical-records.create-from-appointment');

    // Clinical / Treatment Records
    Route::resource('clinical-records', ClinicalRecordController::class);
    Route::get('clinical-records/{clinical_record}/edit-form', [ClinicalRecordController::class, 'editForm'])->name('clinical-records.edit-form');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::post('reports/email', [ReportController::class, 'email'])->name('reports.email');

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
        Route::post('sms/test', [SmsController::class, 'test'])->name('sms.test');
        Route::get('sms/campaign', [SmsController::class, 'campaign'])->name('sms.campaign');
        Route::post('sms/campaign', [SmsController::class, 'sendCampaign'])->name('sms.campaign.send');
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
        Route::post('sms/templates', [SmsController::class, 'storeTemplate'])->name('sms.templates.store');
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
        Route::get('settings/account', [SettingController::class, 'account'])->name('settings.account');
    });
});

/*
|--------------------------------------------------------------------------
| Customer area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('my-account', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('my-account/appointments', [CustomerController::class, 'bookAppointment'])->name('customer.appointments.book');
    Route::put('my-account/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
});
