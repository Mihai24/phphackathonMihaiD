<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BookingsController;

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
  Route::post('/{users:user_id}/add-programmes', [ProgrammesController::class, 'addProgram'])->name('addProgram');
  Route::delete('/{users:user_id}/delete-programmes/{programmes:programme_id}', [ProgrammesController::class, 'deleteProgrammes'])->name('deleteProgrammes');
});

Route::prefix('users')->name('users.')->group(function() {
  Route::get('/', [UsersController::class, 'viewUsers'])->name('viewUsers');
  Route::post('/add-user', [UsersController::class, 'addUser'])->name('addUser');
});

Route::prefix('bookings')->name('bookings.')->group(function() {
  Route::get('/', [BookingsController::class, 'viewBookings'])->name('viewBookings');
  Route::post('/create-booking', [BookingsController::class, 'createBooking'])->name('createBooking');
});
