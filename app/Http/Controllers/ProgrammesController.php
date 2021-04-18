<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Sport;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;

class ProgrammesController extends Controller
{
    public function viewProgrammes(){

      //Extragere programe din db si afisarea acestora

      $programmes = Programme::all();

      return response()->json($programmes);

    }

    public function addProgram(Request $request, $userId){

      // Adaugare program in db

      $user = User::find($userId);

      if (empty($user) || $user->role_id !== 2){
        return response()->json('Datele utilizatorului nu sunt valide.');
      } else {

        $this->validate($request, [
          'participants' => 'required|integer',
          'start_at' => 'required',
          'end_at' => 'required|after:start_at',
          'room_id' => 'required|integer',
          'sport_id' => 'required|integer'
        ]);

        $room = Room::find(trim($request->input('room_id')));
        $sport = Sport::find(trim($request->input('sport_id')));
        $start_datetime = trim($request->input('start_at'));
        $end_datetime = trim($request->input('end_at'));

        // Verificare daca camera sau sportul exista in baza de date

        if (empty($room) || empty($sport)) {
          return response()->json('Camera sau sportul cautat nu se afla in baza de date');
        } else {

          // Verificare pentru toate posibilitatile de suprapuneri
          // ex. fara data: 9:00-11:00 si 9:30-10:30 sau 9:00-11:00 si 8:00-12:00

          $checkDates = Programme::where('room_id', $room->id)
                        ->where(function($q) use ($start_datetime, $end_datetime) {
                          $q->whereBetween('start_at', [$start_datetime, $end_datetime]);
                          $q->whereBetween('end_at', [$start_datetime, $end_datetime]);
                          $q->orWhereRaw('? BETWEEN start_at and end_at', [$start_datetime]);
                          $q->orWhereRaw('? BETWEEN start_at and end_at', [$end_datetime]);
                        })
                        ->first();

          if ($checkDates){
            return response()->json('Nu se poate rezerva sala in intervalul cerut.');
          } else if ($start_datetime == $end_datetime){
            return response()->json('Data de inceput nu poate fi indentica cu data de final.');
          } else {

            $programme = new Programme;
            $programme->participants = trim($request->input('participants'));
            $programme->start_at = $start_datetime;
            $programme->end_at = $end_datetime;
            $programme->room_id = $room->id;
            $programme->sport_id = $sport->id;
            $programme->save();

            return response()->json($programme);
          }
        }
      }
    }

    public function deleteProgrammes($userId, $programmeId){

      // Stergere programe

      $user = User::find($userId);

      // Verificare daca utilizatorul exista sau daca este admin

      if (empty($user) || $user->role_id !== 2){
        return response()->json('Datele utilizatorului nu sunt valide.');
      } else {

        $programme = Programme::find($programmeId);

        if (!$programme){
          return response()->json('Programul nu este valid.');
        } else {

          $bookings = Booking::where('programme_id', $programmeId)->get();

          // Verificare daca programul are rezervari inregistrate

          if (!$bookings){
            $programme->delete();
            return response()->json('Programul a fost sters');
          } else {

            // Daca are rezervari stergem fiecare rezervare dupa care stergem programul

            foreach($bookings as $booking){
              $booking->delete();
            }
            $programme->delete();
            return response()->json('Programul a fost sters');
          }
        }
      }
    }
}
