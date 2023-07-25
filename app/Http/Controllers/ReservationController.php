<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Provider;
use App\Models\Room;
use App\Models\Service;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{

    public function create()
    {
        $user = Auth::user();
        $serviceType = ServiceType::where('type_name', 'Reservation')->firstOrFail();
        $buildings = Building::all();
        $services = Service::with('providers', 'providers.user')->where('service_type_id', $serviceType->id)->get();
        return view('reservation', [
            'user' => $user,
            'services' => $services,
            'serviceType' => $serviceType,
            'buildings' => $buildings, // Add the 'buildings' variable here
        ]);
    }




    public function store(Request $request)
    {
        \Log::info('Inside store method');
        $validator = Validator::make($request->all(), [
            'start_date_time' => 'required',
            'end_date_time' => [
                'required',
                'after:start_date_time'
            ],
            'title' => 'nullable',
            'description' => 'nullable',
            'number_places' => 'integer|nullable',
            'service_type_id' => 'required|exists:service_types,id',
            'provider_id' => 'required|exists:providers,id', // Add validation for provider
        ]);

        $validator->sometimes('end_date_time', 'after:start_date_time', function ($input) {
            return isset($input->start_date_time);
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $service = new Service();
        $service->start_date_time = $request->input('start_date_time');
        $service->end_date_time = $request->input('end_date_time');
        $service->title = $request->input('title');
        $service->description = $request->input('description');
        $service->number_places = $request->input('number_places');
        $service->service_type_id = $request->input('service_type_id');
        $service->cost = 100;

        $providerId = $request->input('provider_id');
        $commission = 5; // Use the actual commission value here


        $service->save();

        // Attach the provider to the service
        $service->providers()->attach($providerId, [
            'commission' => $commission,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Check if the service is an atelier or formations professionnelles
        $serviceType = ServiceType::findOrFail($request->input('service_type_id'));
        if ($serviceType->type_name === 'Ateliers' || $serviceType->type_name === 'Formations professionnelles') {
            // Create the service_building relationship
            $buildingId = $request->input('building');
            $building = Building::findOrFail($buildingId);
            $service->buildings()->attach($building, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // New code: fetch and update the room
            $roomId = $request->input('room');
            $room = Room::findOrFail($roomId);
            if ($service->number_places > $room->max_capacity) {
                return redirect()->back()->withErrors(['The number of places exceeds the room\'s capacity.'])->withInput();
            }
            $room->is_reserved = true;
            $room->save();
        }
        if ($serviceType->type_name === 'Cours en ligne') {
            session()->put('liveStreamData', [
                'title' => $service->title,
                'description' => $service->description,
                'start_date_time' => $service->start_date_time,
                'end_date_time' => $service->end_date_time,
                'service_id' => $service->id,
            ]);

            return redirect()->route('livestream.authorize');
        }

        \Log::info('Redirecting to user profile');


        return redirect()->route('user.profile');
    }
}
