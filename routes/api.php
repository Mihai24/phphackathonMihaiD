<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AppointmentsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('programmes')->name('programmes.')->group(function() {
  Route::get('/', [ProgrammesController::class, 'viewProgrammes'])->name('viewProgrammes');
  Route::post('/add-programmes', [ProgrammesController::class, 'addProgram'])->name('addProgram');
  Route::delete('/delete-programmes/{id}', [ProgrammesController::class, 'deleteProgrammes'])->name('deleteProgrammes');
});

Route::prefix('users')->name('users.')->group(function() {
  Route::get('/', [UsersController::class, 'viewUsers'])->name('viewUsers');
  Route::post('/add-user', [UsersController::class, 'addUser'])->name('addUser');
});

Route::prefix('appointments')->name('appointments.')->group(function() {
  Route::get('/', [AppointmentsController::class, 'viewAppointments'])->name('viewAppointments');
  Route::post('/create-appointment', [AppointmentsController::class, 'createAppointment'])->name('createAppointment');
});
