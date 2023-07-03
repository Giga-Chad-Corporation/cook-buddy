<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $role = 'User'; // Default role
        $provider_type = null;

        // Check if the user is a provider
        $provider = Provider::where('user_id', $user->id)->first();
        if ($provider) {
            $role = 'Provider';
            $provider_type = $provider->providerType->type_name; // Access provider type's name
        }

        return view('user.profile', ['user' => $user, 'role' => $role, 'provider_type' => $provider_type]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Make sure the email is not being changed
        if ($user->email !== $request->input('email')) {
            return response()->json(['error' => 'Email change is not allowed'], 400);
        }

        // Validate the request...

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

        // Return JSON response with the updated user data
        return response()->json($user);
    }

    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->store('profile_pictures', 'public');

            // Update the user's profile_photo_path field with the new file path
            $user->profile_photo_path = $path;

            $user->save();

            // Return the updated user data or just the profile picture URL if needed
            return response()->json(['profile_picture' => asset('storage/' . $path)]);
        }


        return response()->json(['error' => 'No profile picture uploaded'], 400);
    }
}
