<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function viewUsers(){

      $users = User::all();

      return response()->json($users);

    }

    public function addUser(Request $request){

      $this->validate($request, [
        'name' => 'required|min:3|max:32',
        'cnp' => 'required|size:13|regex:/^[0-9]+$/|unique:users'
      ]);

      $user = new User;
      $user->name = trim($request->input('name'));
      $user->cnp = trim($request->input('cnp'));
      $user->save();

      return response()->json($user);
    }

}
