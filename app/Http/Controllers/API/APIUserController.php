<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class APIUserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'phone.required' => 'A phone number is required',
            'phone.numeric' => 'Phone number should be only numbers',
            'phone.digits_between' => 'Phone number should be between 10 and 15 digits',
        ];

        // Validate the request...
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'nullable|numeric|digits_between:10,15',
            'description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Retrieve the request data
        $requestData = $request->only(['first_name', 'last_name', 'username', 'phone', 'description', 'address']);

        // Update the user model
        $user->fill($requestData);

        $user->save();

        // Return the JSON response with the success message and updated user
        return response()->json(['message' => 'Profile updated successfully.', 'user' => $user]);
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

        // Eager load the subscription and plan relationships
        $user->load('subscription.plan');

        // Retrieve the user's subscription and plan
        $subscription = $user->subscription;
        $plan = $subscription ? $subscription->plan : null;

        // Add the profile picture path to the user object
        $profilePicturePath = asset('storage/' . $user->profile_photo_path);

        // Return the user profile information as JSON response along with role, provider type, plan, subscription, and profile picture path
        return response()->json([
            'user' => $user,
            'role' => $role,
            'providerType' => $providerType,
            'plan' => $plan,
            'subscription' => $subscription,
            'profile_photo_path' => $profilePicturePath,
        ]);
    }



    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();

        // Validate the request...
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Retrieve the profile picture file
        $profilePicture = $request->file('profile_picture');

        // Generate a unique filename for the profile picture
        $filename = time() . '_' . $profilePicture->getClientOriginalName();

        // Store the profile picture in the storage/app/public directory
        $profilePicture->storeAs('profile_pictures', $filename, 'public');

        // Update the profile_photo_path attribute of the user
        $user->profile_photo_path = 'profile_pictures/'.$filename;

        // Save the updated user model
        $user->save();

        // Return the JSON response with the success message
        return response()->json(['success' => true, 'message' => 'Profile picture updated successfully']);
    }

    public function getServices(Request $request)
    {
        $user = Auth::user();
        $services = $user->services()->with('serviceType')->get();

        $servicesWithProvider = $services->map(function ($service) {
            $service->provider = $service->users->first()->only(['id', 'username', 'first_name', 'last_name', 'profile_photo_path']);
            $service->service_type = $service->serviceType->only(['id', 'type_name']);

            return $service->only(['id', 'title', 'start_date_time', 'end_date_time', 'cost', 'service_type', 'provider']);
        });

        return response()->json(['services' => $servicesWithProvider]);
    }
}
