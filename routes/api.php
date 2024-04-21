<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\DefaultController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('test', [DoctorController::class, 'getPatientDetails']);
Route::post('createPatient', [DoctorController::class, 'createPatient']);
Route::get('getPatientById/{id}', [DoctorController::class, 'getPatientById']);
Route::post('createAppointment/{patient_id}/{doctor_id}', [DoctorController::class, 'createAppointment']);
Route::get('getAppointments', [DoctorController::class, 'getAppointments']);
Route::post('createTest/{appointment_id}/{test_type}', [DoctorController::class, 'createTest']);
Route::get('getTests', [DoctorController::class, 'getTests']);
Route::post('createPayment', [DoctorController::class, 'createPayment']);
Route::get('getPayments', [DoctorController::class, 'getPayments']);
Route::post('createDrugOrder', [DoctorController::class, 'createDrugOrder']);
Route::get('getDrugOrders', [DoctorController::class, 'getDrugOrders']);
Route::post('createFlight/{test_id}', [LabController::class, 'createFlight']);
Route::get('getFlights', [LabController::class, 'getFlights']);
Route::post('createResult/{test_id}/{user_id}', [LabController::class, 'createResult']);
Route::get('getResults', [LabController::class, 'getResults']);
Route::get('getPatientResults/{id}', [LabController::class, 'getPatientResults']);
Route::put('updateResult/{id}', [LabController::class, 'updateResult']);
Route::post('callbackUrl', [DefaultController::class, 'callbackUrl']);
// Route::post('ussdCallback', [DefaultController::class, 'ussdCallback']);
Route::any('ussdCallback', [DefaultController::class, 'ussdCallback']);
Route::post('gemini/{prompt}', [DefaultController::class, 'gemini']);
Route::post('createUser', [DefaultController::class, 'createUser']);
Route::post('login', [DefaultController::class, 'login']);

