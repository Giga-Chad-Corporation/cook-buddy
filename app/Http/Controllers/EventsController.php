<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventsController extends Controller
{
    public function index()
    {
        // Retrieve the user's subscription plan.
        $user = Auth::user();
        $userPlan = $user->subscription->plan ?? null;

        // Determine if the user has a master plan.
        $hasMasterPlan = ($userPlan == 'master');

        // Retrieve the upcoming events.
        $events = Event::where('date', '>', now())->orderBy('date')->get();

        // Return the events view with the appropriate data.
        return view('event', [
            'hasMasterPlan' => $hasMasterPlan,
            'events' => $events,
        ]);
    }
}
