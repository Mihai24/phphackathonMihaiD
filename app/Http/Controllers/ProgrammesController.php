<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;

class ProgrammesController extends Controller
{
    public function viewProgrammes(){//Extragere programe din db si afisarea acestora

      $programmes = Programme::all();

      return response()->json($programmes);

    }

    public function addProgram(Request $request){//Adaugare program in db

      $this->validate($request, [
        'participants' => 'required|integer',
        'start_at' => 'required|date_format:Y-m-d H:i:s',
        'end_at' => 'required|date_format:Y-m-d H:i:s',
        'room_id' => 'required|integer'
        'sport_id' => 'required|integer'
      ]);

      $start_datetime = $request->input('start_at');
      $end_datetime = $request->input('end_at');

      $checkDates = Programme::where('room', $request->input('room'))
                    ->whereBetween('start_at', [$start_datetime, $end_datetime])
                    ->whereBetween('end_at', [$start_datetime, $end_datetime])
                    ->orWhereRaw('? BETWEEN start_at and end_at', [$start_datetime])
                    ->orWhereRaw('? BETWEEN start_at and end_at', [$end_datetime])
                    ->first();

      if ($checkDates){
        return response()->json('Nu se poate rezerva sala in intervalul cerut.');
      } else if ($start_datetime == $end_datetime){
        return response()->json('Data de inceput nu poate fi indentica cu data de final.');
      } else {

        $programme = new Programme;
        $programme->program_name = trim($request->input('program_name'));
        $programme->participants = trim($request->input('participants'));
        $programme->start_at = $start_datetime;
        $programme->end_at = $end_datetime;
        $programme->room = trim($request->input('room'));
        $programme->save();

        return response()->json($programme);
      }
    }

    public function deleteProgrammes($programmeId){

      $programme = Programme::find($programmeId);

      if (empty($programme)){
        return response()->json('Programul nu este valid.');
      } else {
        $programme->delete();
        return response()->json('Programul a fost sters');
      }
    }
}
