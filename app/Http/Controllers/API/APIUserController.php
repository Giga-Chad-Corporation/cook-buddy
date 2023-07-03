<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class APIUserController extends Controller
{
    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        // Valider la requête...

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->description = $request->input('description');

        $user->save();

        // Retourner la réponse JSON avec les données utilisateur mises à jour
        return response()->json($user);
    }
}

