<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Provider;
use App\Models\ProviderType;
use App\Models\Room;
use App\Models\Service;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ServiceController extends Controller
{

    public function getAvailableProviders(Request $request)
    {
        // Define the provider types
        $providerTypes = ['Chef cuisinier'];

        $startDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_date_time);
        $endDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_date_time);

        // Fetch all providers who have availability during the service time
        $providers = Provider::with(['regions' => function ($query) use ($startDate, $endDate) {
            $query->where('available_date', $startDate->format('Y-m-d'))
                ->whereTime('start_time', '<=', $startDate->format('H:i:s'))
                ->whereTime('end_time', '>=', $endDate->format('H:i:s'));
        }, 'user'])->whereHas('regions', function ($query) use ($startDate, $endDate) {
            $query->where('available_date', $startDate->format('Y-m-d'))
                ->whereTime('start_time', '<=', $startDate->format('H:i:s'))
                ->whereTime('end_time', '>=', $endDate->format('H:i:s'));
        })->whereDoesntHave('services', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date_time', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
                ->orWhereBetween('end_date_time', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        })->whereHas('providerType', function ($query) use ($providerTypes) {
            $query->whereIn('type_name', $providerTypes);
        })->get();

        // Filter out providers with no regions available
        $providers = $providers->reject(function ($provider) {
            return $provider->regions->isEmpty();
        });

        return response()->json($providers);
    }



    public function createCoursADomicile()
    {
        $admin = Auth::guard('admin')->user();
        $user = Auth::guard('web')->user();
        $serviceType = ServiceType::where('type_name', 'Cours à domicile')->firstOrFail();

        if ($admin && $admin->is_super_admin) {
            $serviceType = ServiceType::where('type_name', 'Cours à domicile')->firstOrFail();
            return view('formation.cours-a-domicile.create', compact('serviceType'));
        } elseif($user && $user->is_provider) {
            return view('formation.cours-a-domicile.provider');
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('providers', 'providers.user')->where('service_type_id', $serviceType->id)->get();

            return view('formation.cours-a-domicile.index', compact('user', 'services'));
        }
        else {
            return redirect()->route('login');
        }
    }

    public function ateliers()
    {
        $user = Auth::user();
        $admin = Auth::guard('admin')->user();
        $serviceType = ServiceType::where('type_name', 'Ateliers')->firstOrFail();
        $buildings = Building::all();

        if ($admin && $admin->is_super_admin) {
            return view('formation.ateliers.create', compact('serviceType', 'buildings'));
        }elseif($user && $user->is_provider) {
            return view('formation.cours-a-domicile.provider');
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('providers', 'providers.user')->where('service_type_id', $serviceType->id)->get();
            return view('formation.ateliers.index', compact('user', 'services'));
        } else {
            return redirect()->route('login');
        }
    }

    public function createCoursEnLigne()
    {
        $user = Auth::user();
        $admin = Auth::guard('admin')->user();
        $serviceType = ServiceType::where('type_name', 'Cours en ligne')->firstOrFail();
        $buildings = Building::all();;

        if ($admin && $admin->is_super_admin) {
            return view('formation.cours-en-ligne.create', compact('serviceType', 'buildings'));
        }elseif($user && $user->is_provider) {
            return view('formation.cours-a-domicile.provider');
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('providers', 'providers.user')->where('service_type_id', $serviceType->id)->get();
            return view('formation.cours-en-ligne.index', compact('user', 'services'));
        } else {
            return redirect()->route('login');
        }
    }

    public function formationsProfessionnelles()
    {
        $user = Auth::user();
        $admin = Auth::guard('admin')->user();
        $serviceType = ServiceType::where('type_name', 'Formations Professionnelles')->firstOrFail();
        $buildings = Building::all();

        if ($admin && $admin->is_super_admin) {
            return view('formation.formations-professionnelles.create', compact('serviceType', 'buildings'));
        }elseif($user && $user->is_provider) {
            return view('formation.cours-a-domicile.provider');
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('providers', 'providers.user')->where('service_type_id', $serviceType->id)->get();
            return view('formation.formations-professionnelles.index', compact('user', 'services'));
        }else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.users');
        }
    }


    public function store(Request $request)
    {
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
            'cost' => 'required|numeric|min:0',
            'provider_id' => 'required|exists:providers,id', // Add validation for provider
            'picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
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
        $service->number_places = $request->filled('number_places') ? $request->input('number_places') : 1;
        $service->service_type_id = $request->input('service_type_id');
        $service->cost = $request->input('cost');

        $providerId = $request->input('provider_id');
        $commission = 5; // Use the actual commission value here

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $filename = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $extension;
            $picturePath = $file->storeAs('services', $filename, 'public');
            $service->picture = $picturePath;
        }

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

        return redirect()->route('formation')->with('success', 'Service created successfully.');
    }

    public function addServiceToUser(Request $request)
    {
        $serviceId = $request->input('service_id');
        $user = Auth::user();

        if ($user && $user->isProvider()) {
            return response()->json(['message' => 'Les prestataires ne peuvent pas participer'], 403);
        }

        // Check if the user has an address
        if ($user && !$user->address) {
            return response()->json(['message' => 'Ajouter votre addresse s\'il vous plait'], 403);
        }

        if ($user && $serviceId) {
            $service = Service::find($serviceId);

            if (!$service) {
                return response()->json(['message' => 'Service not found.'], 404);
            }

            $remainingPlaces = $service->number_places - $service->users()->count();

            if ($remainingPlaces <= 0) {
                return response()->json(['message' => 'Plus de places disponibles'], 400);
            }

            $existingUser = $service->users()->where('user_id', $user->id)->first();

            if ($existingUser) {
                return response()->json(['message' => 'Tu participe déja à ce service !'], 400);
            }

            $user->services()->attach($serviceId, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $remainingPlaces--;

            $service->update([
                'number_places' => $remainingPlaces,
            ]);

            return response()->json(['message' => 'Service added to user.'], 200);
        }

        return response()->json(['message' => 'Unauthorized.'], 401);
    }

}
