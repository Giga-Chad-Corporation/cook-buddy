<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $currentUserPlan = Auth::user()->subscription->plan ?? null;

        return view('plans.index', compact('plans', 'currentUserPlan'));
    }



    public function subscribeFree($planId)
    {
        $plan = Plan::find($planId);

        if (!$plan) {
            return redirect()->route('plans.index')->with('error', 'Invalid plan selected.');
        }

        $user = Auth::user();

        // If the plan is free
        if ($plan->price <= 0) {
            if ($user->subscription) {
                $user->subscription->plan_id = $plan->id;
                $user->subscription->save();
            } else {
                $subscription = new Subscription;
                $subscription->plan_id = $plan->id;
                $user->subscription()->save($subscription);
            }

            return redirect()->route('user.profile')->with('success', 'Successfully subscribed to the free plan.');
        } else {
            // If the plan is not free, redirect to the payment route
            return redirect()->route('create-checkout-session', ['planId' => $plan->id]);
        }
    }
}

