<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function viewUsers(){ //Extragere utilizatori din db si afisarea acestora

      $users = User::all();

      return response()->json($users);

    }

    public function addUser(Request $request){ //Inregistrare utilizator nou

      $this->validate($request, [
        //Validare nume: min. 3 caracter, max. 32 si camp obligatori
        'name' => 'required|min:3|max:32',
        //Validare cnp: camp obligatoriu, sa fie unic in db ,lungime 13 si sa cuprinda doar cifre
        'cnp' => 'required|size:13|regex:/^[0-9]+$/|unique:users'
      ]);

      //Adaugare user in db dupa validare
      $user = new User;
      $user->name = trim($request->input('name'));
      $user->cnp = trim($request->input('cnp'));
      $user->save();

      return response()->json($user);
    }

}
