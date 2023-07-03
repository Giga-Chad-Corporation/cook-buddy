<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        $role = 'User'; // Rôle par défaut
        $providerType = null;

        // Vérifier si l'utilisateur est un prestataire
        $provider = Provider::where('user_id', $user->id)->first();
        if ($provider) {
            $role = 'Provider';
            $providerType = $provider->providerType->type_name; // Accéder au nom du type de prestataire
        }

        return view('user.profile', compact('user', 'role', 'providerType'));
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Assurez-vous que l'email ne soit pas modifié
        if ($user->email !== $request->input('email')) {
            return response()->json(['error' => 'La modification de l\'email n\'est pas autorisée'], 400);
        }

        // Valider la requête...

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->description = $request->input('description');

        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Rediriger vers la page de profil avec un message de succès
        return redirect()->route('user.profile')->with('success', 'Profil mis à jour avec succès.');
    }
}

