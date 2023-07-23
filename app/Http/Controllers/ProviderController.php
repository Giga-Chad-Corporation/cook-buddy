<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    // Other methods...

    public function regions()
    {
        $regions = Region::all();
        $provider = Provider::find(Auth::user()->id);

        // Get the regions where this provider is available
        $availabilities = $provider->regions()->get();

        return view('provider.availability', compact('regions', 'availabilities'));
    }


    public function updateAvailability(Request $request)
    {
        if (!Auth::check()) {
            // Redirect the user to the login page
            return redirect()->route('login');
        }

        $provider = Provider::find(Auth::user()->id);

        $provider->regions()->syncWithoutDetaching([
            $request->input('region') => [
                'available_date' => $request->input('date'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
            ]
        ]);

        return redirect()->route('user.profile');
    }

    public function editAvailability($id)
    {
        // Fetch the current provider
        $provider = Provider::find(Auth::user()->id);

        // Fetch the desired availability (region)
        $availability = $provider->regions()->where('region_id', $id)->first();

        // Check if the logged in user is the owner of this availability
        if (!$availability) {
            // Redirect to some error page
            return response()->json(['error' => 'Availability not found'], 404);
        }

        // Render the edit form passing the current availability data
        return response()->json(['success' => true, 'availability' => $availability]);
    }


    public function deleteAvailability($id)
    {
        // Fetch the current provider
        $provider = Provider::find(Auth::user()->id);

        // Remove the specific availability (region)
        $provider->regions()->detach($id);

        // Return success response
        return response()->json(['success' => true]);
    }





}
