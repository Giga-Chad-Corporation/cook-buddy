<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIUserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Retrieve the authenticated user

        // Validate the request...

        $user->fill($request->all()); // Update the user model with the request data

        $user->save();

        // Return the JSON response with the updated user data
        return response()->json($user);
    }

    public function showProfile(Request $request)
    {
        $user = Auth::user();
        $role = 'Client'; // Default role
        $providerType = null;

        // Check if the user is a provider
        $provider = Provider::where('user_id', $user->id)->first();
        if ($provider) {
            $role = 'Prestataire';
            $providerType = $provider->providerType->type_name; // Access the provider type name
        }

        // Return the user profile information as JSON response along with role and provider type
        return response()->json([
            'user' => $user,
            'role' => $role,
            'providerType' => $providerType,
        ]);
    }








    public function updateProfilePicture(Request $request)
    {
        // Validate and process the profile picture update

        // Return the JSON response with the updated user data
        return response()->json(['message' => 'Profile picture updated successfully']);
    }
}

