<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Programme;

class BookingsController extends Controller
{
    public function viewBookings(){

      $bookings = Booking::all();

      return response()->json($bookings);

    }

    public function createBooking(Request $request){

      $this->validate($request, [
        'cnp' => 'required|size:13|regex:/^[0-9]+$/|',
        'programme_id' => 'required|integer'
      ]);

      $user = User::where('cnp', trim($request->input('cnp')))->first();
      $programme = Programme::find($request->input('programme_id'));

      //Verificare daca utilizatorul sau programul exista in db

      if (empty($user) || empty($programme)){
        return response()->json('Utilizatorul sau programul nu se afla in baza de date.');
      }

      // Verificare daca utilizatorul are rezervare

      $isUserRegistered = Booking::where('user_id', $user->id)->where('programme_id', $programme->id)->first();

      if ($isUserRegistered) {
        return response()->json('Sunteti inscris la acest program.');
      }

      // Verificare numar persoane inscrise la programul respectiv

      $checkParticipants = Booking::join('programmes', 'bookings.programme_id', '=', 'programmes.id')
                                    ->where('bookings.programme_id', $programme->id)
                                    ->get();

      if (count($checkParticipants) == $programme->participants) {
        return response()->json('Nu mai sunt locuri disponibile pentru acest program.');
      } else {

        // Verificare pentru toate posibilitatile de suprapuneri
        // ex. fara data: 9:00-11:00 si 9:30-10:30 sau 9:00-11:00 si 8:00-12:00

        $datesOverlap = Booking::join('programmes', 'bookings.programme_id', '=', 'programmes.id')
                                ->where('bookings.user_id', $user->id)
                                ->where(function($q) use ($programme) {
                                  $q->whereBetween('programmes.start_at', [$programme->start_at, $programme->end_at]);
                                  $q->whereBetween('programmes.end_at', [$programme->start_at, $programme->end_at]);
                                  $q->orWhereRaw('? BETWEEN programmes.start_at and programmes.end_at', [$programme->start_at]);
                                  $q->orWhereRaw('? BETWEEN programmes.start_at and programmes.end_at', [$programme->end_at]);
                                })->first();

        if ($datesOverlap) {
          return response()->json('Nu puteti participa, deoarece in acest interval de timp sunteti inscris la camera '.$programme->room->room_number);
        } else {
          $booking = new Booking;
          $booking->user_id = $user->id;
          $booking->programme_id = $programme->id;
          $booking->save();

          return response()->json('Participarea dvs. la acest program a fost inregistrata.');
        }
      }
    }
}
