<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;

class ProgrammesController extends Controller
{
    public function viewProgrammes(){

      $programmes = Programme::all();

      return response()->json($programmes);

    }

    public function addProgram(Request $request){

      $this->validate($request, [
        'program_name' => 'required|min:1|max:60',
        'participants' => 'required|integer',
        'start_at' => 'required',
        'end_at' => 'required',
        'room' => 'required|integer'
      ]);

      $start_datetime = $request->input('start_at');
      $end_datetime = $request->input('end_at');

      $checkDates = Programme::where('room', $request->input('room'))
                    ->whereBetween('start_at', [$start_datetime, $end_datetime])
                    ->orWhereBetween('end_at', [$start_datetime, $end_datetime])
                    ->orWhereRaw('? BETWEEN start_at and end_at', [$start_datetime])
                    ->orWhereRaw('? BETWEEN start_at and end_at', [$end_datetime])
                    ->first();

      return response()->json($checkDates);

      if ($checkDates){
        return response()->json('Nu se poate rezerva sala in intervalul cerut.');
      }

      if ($start_datetime == $end_datetime){
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
