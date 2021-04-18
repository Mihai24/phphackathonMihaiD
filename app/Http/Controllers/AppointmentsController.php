<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Programme;

class AppointmentsController extends Controller
{
    public function viewAppointments(){

      $appointments = Appointment::all();

      return response()->json($appointments);

    }

    public function createAppointment(Request $request){

      $this->validate($request, [
        'user_id' => 'required|integer',
        'programme_id' => 'required|integer'
      ]);

      $user = User::find($request->input('user_id'));
      $programme = Programme::find($request->input('programme_id'));

      if (empty($user) || empty($programme)){
        return response()->json('Utilizatorul sau programul nu se afla in baza de date.');
      }

      if (!empty(Appointment::where('user_id', $user->id)->where('program_id', $programme->id)->first())) {
        return response()->json('Utilizatorul este programat la acest program.');
      }

      $checkParticipants = Appointment::join('programmes', 'appointments.program_id', '=', 'programmes.id')
                                ->where('appointments.program_id', $programme->id)
                                ->get();

      if (count($checkParticipants) == $programme->participants) {
        return response()->json('Nu mai sunt locuri disponibile pentru acest program.');
      } else {
        $checkDate = Appointment::join('programmes', 'appointments.program_id', '=', 'programmes.id')
                                ->where('appointments.user_id', $user->id)
                                ->whereBetween('programmes.start_at', [$programme->start_at, $programme->end_at])
                                ->orWhereBetween('programmes.end_at', [$programme->start_at, $programme->end_at])
                                ->orWhereRaw('? BETWEEN programmes.start_at and programmes.end_at', [$programme->start_at])
                                ->orWhereRaw('? BETWEEN programmes.start_at and programmes.end_at', [$programme->end_at])
                                ->first();

        if (!empty($checkDate)) {
          return response()->json('Aceasta data nu este disponibila.');
        } else {
          $appointment = new Appointment;
          $appointment->user_id = $user->id;
          $appointment->programme_id = $programme->id;
          $appointment->save();

          return response()->json($appointment);
        }
      }
    }
}
