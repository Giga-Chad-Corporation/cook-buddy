<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class APIUserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate the request...
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);

        // Retrieve the request data
        $requestData = $request->only(['first_name', 'last_name', 'username', 'address', 'phone', 'description']);

        Log::info('Request data:', $requestData);

        $user->update($requestData);


        // Save the updated user model
        $user->save();

        // Return the JSON response with the success message
        return response()->json(['message' => 'Profile updated successfully.']);
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

