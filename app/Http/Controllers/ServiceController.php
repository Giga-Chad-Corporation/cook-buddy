<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\ProviderType;
use App\Models\Service;
use App\Models\ServiceType;
use Carbon\Carbon;
use Google_Service_YouTube_LiveBroadcastContentDetails;
use Google_Service_YouTube_LiveStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Google\Client as Google_Client;
use Google\Service\YouTube as Google_Service_YouTube;
use Google\Service\YouTube\LiveBroadcast as Google_Service_YouTube_LiveBroadcast;
use Google\Service\YouTube\LiveBroadcastSnippet as Google_Service_YouTube_LiveBroadcastSnippet;
use Google\Service\YouTube\LiveBroadcastStatus as Google_Service_YouTube_LiveBroadcastStatus;


class ServiceController extends Controller
{
    public function createCoursADomicile()
    {
        $user = Auth::user();
        $serviceType = ServiceType::where('type_name', 'Cours à domicile')->firstOrFail();

        if ($user && $user->isProvider() && $user->provider->providerType->type_name === 'Chef cuisinier') {
            // Check if the provider has an address
            if ($user->address) {
                return view('formation.cours-a-domicile.create', compact('serviceType'));
            } else {
                return redirect()->route('user.profile')->with('error', 'Met à jour ton adresse pour pouvoir créer un cours à domicile.');
            }
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('users.provider')->where('service_type_id', $serviceType->id)->get();
            return view('formation.cours-a-domicile.index', compact('user', 'services'));
        } else {
            return redirect()->route('login'); // Redirect to the login page, adjust the route as needed
        }
    }

    public function ateliers()
    {
        $user = Auth::user();
        $serviceType = ServiceType::where('type_name', 'Ateliers')->firstOrFail();
        $buildings = Building::all();

        if ($user && $user->isProvider() && $user->provider->providerType->type_name === 'Chef cuisinier') {
            // Check if the provider has an address
            if ($user->address) {
                return view('formation.ateliers.create', compact('serviceType', 'buildings'));
            } else {
                $errorMessage = 'Met à jour ton adresse pour pouvoir créer un atelier.';
                return view('formation.ateliers.index', compact('user', 'services', 'errorMessage'));
            }
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('users.provider')->where('service_type_id', $serviceType->id)->get();
            return view('formation.ateliers.index', compact('user', 'services'));
        } else {
            return redirect()->route('login');
        }
    }

    public function createCoursEnLigne()
    {
        $user = Auth::user();
        $serviceType = ServiceType::where('type_name', 'Cours en ligne')->firstOrFail();

        if ($user && $user->isProvider() && $user->provider->providerType->type_name === 'Chef cuisinier') {
            // Check if the provider has an address
            if ($user->address) {
                return view('formation.cours-en-ligne.create', compact('serviceType'));
            } else {
                return redirect()->route('user.profile')->with('error', 'Mets à jour ton adresse pour pouvoir créer un cours en ligne.');
            }
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('users.provider')->where('service_type_id', $serviceType->id)->get();
            return view('formation.cours-en-ligne.index', compact('user', 'services'));
        } else {
            return redirect()->route('login'); // Redirect to the login page, adjust the route as needed
        }
    }

    public function formationsProfessionnelles()
    {
        $user = Auth::user();
        $serviceType = ServiceType::where('type_name', 'Formations Professionnelles')->firstOrFail();
        $buildings = Building::all();

        if ($user && $user->isProvider() && $user->provider->providerType->type_name === 'Chef de formation') {
            // Check if the provider has an address
            if ($user->address) {
                return view('formation.formations-professionnelles.create', compact('serviceType', 'buildings'));
            } else {
                return redirect()->route('user.profile')->with('error', 'Mets à jour ton adresse pour pouvoir créer un cours en ligne.');
            }
        } elseif ($user && !$user->isProvider()) {
            $services = Service::with('users.provider')->where('service_type_id', $serviceType->id)->get();
            return view('formation.formations-professionnelles.index', compact('user', 'services'));
        } else {
            return redirect()->route('login');
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
            'picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Add validation rules for the picture
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
        $service->number_places = $request->input('number_places') + 1;
        $service->service_type_id = $request->input('service_type_id');
        $service->cost = $request->input('cost');

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $filename = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $extension;
            $picturePath = $file->storeAs('services', $filename, 'public');
            $service->picture = $picturePath;
        }

        $service->save();

        $user = Auth::user();

        $user->services()->attach($service->id, [
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
        }

        return redirect()->route('user.profile')->with('success', 'Service created successfully.');
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

            // Update the remaining places for the service
            $remainingPlaces--;

            $service->update([
                'number_places' => $remainingPlaces,
            ]);

            return response()->json(['message' => 'Service added to user.'], 200);
        }

        return response()->json(['message' => 'Unauthorized.'], 401);
    }

//    public function createYouTubeLivestream(Request $request)
//    {
//        // Validate the request data
//        $request->validate([
//            'title' => 'required',
//            'description' => 'nullable',
//            'start_date_time' => 'required|date',
//            'end_date_time' => 'required|date|after:start_date_time',
//        ]);
//
//        // Create a new YouTube Live Broadcast
//        $client = new Google_Client();
//        $client->setDeveloperKey(config('services.google.youtube_key'));
//
//        $youtube = new Google_Service_YouTube($client);
//
//        // Create a Live Broadcast object
//        $broadcast = new Google_Service_YouTube_LiveBroadcast();
//
//        // Set the broadcast snippet
//        $snippet = new Google_Service_YouTube_LiveBroadcastSnippet();
//        $snippet->setTitle($request->input('title'));
//        $snippet->setDescription($request->input('description'));
//        $snippet->setScheduledStartTime($request->input('start_date_time'));
//        $snippet->setScheduledEndTime($request->input('end_date_time'));
//
//        $broadcast->setSnippet($snippet);
//
//        // Set the broadcast status
//        $status = new Google_Service_YouTube_LiveBroadcastStatus();
//        $status->setPrivacyStatus('private'); // Set the privacy status as per your requirement
//
//        $broadcast->setStatus($status);
//
//        // Insert the live broadcast
//        $broadcast = $youtube->liveBroadcasts->insert('snippet,status', $broadcast);
//
//        // Create a Live Stream object
//        $stream = new Google_Service_YouTube_LiveStream();
//
//        // Set the stream snippet
//        $streamSnippet = new Google_Service_YouTube_LiveStreamSnippet();
//        $streamSnippet->setTitle($request->input('title'));
//        $streamSnippet->setScheduledStartTime($request->input('start_date_time'));
//        $streamSnippet->setScheduledEndTime($request->input('end_date_time'));
//
//        $stream->setSnippet($streamSnippet);
//
//        // Set the stream details
//        // ...
//
//        // Insert the live stream
//        $stream = $youtube->liveStreams->insert('snippet,status', $stream);
//
//        // Bind the live broadcast with the live stream
//        $bindRequest = $youtube->liveBroadcasts->bind($broadcast->id, 'id,contentDetails');
//        $bindRequest->setStreamId($stream->id);
//        $bindResponse = $bindRequest->execute();
//
//        // Retrieve the broadcast and stream IDs
//        $broadcastId = $bindResponse->id;
//        $streamId = $bindResponse->contentDetails->boundStreamId;
//
//        // Return the IDs or any other relevant data as needed
//        return response()->json([
//            'broadcast_id' => $broadcastId,
//            'stream_id' => $streamId,
//        ]);
//    }



}
