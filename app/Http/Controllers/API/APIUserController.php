<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Add the profile picture path to the user object
        $profilePicturePath = asset('storage/' . $user->profile_photo_path);

        // Return the user profile information as JSON response along with role, provider type, and profile picture path
        return response()->json([
            'user' => $user,
            'role' => $role,
            'providerType' => $providerType,
            'profile_photo_path' => $profilePicturePath,
        ]);
    }
    public function AllUsers(Request $request)
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }
    public function Top5FidelUsers(Request $request)
    {
        $users = User::orderBy('fidelity', 'desc')->take(5)->get();
        return response()->json(['users' => $users]);
    }

    function EventPourcentageByType()
    {

        $totalServices = Service::count();

        $serviceStats = Service::with('serviceType')
            ->get()
            ->groupBy('serviceType.type_name')
            ->map(function ($services, $type) use ($totalServices) {
                return [
                    'type' => $type,
                    'total' => count($services),
                    'percentage' => (count($services) / $totalServices) * 100,
                ];
            })
            ->values();

        return response()->json($serviceStats);

    }
function EventPourcentageByFrequency(){

    $totalServices = Service::count();

    $serviceStats = Service::select(DB::raw('DATE(start_date_time) as date'), DB::raw('count(*) as total'))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->map(function ($item) use ($totalServices) {
            $item->percentage = ($item->total / $totalServices) * 100;
            return $item;
        });

    return response()->json($serviceStats);

}
    function Top5event(){}



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
}
