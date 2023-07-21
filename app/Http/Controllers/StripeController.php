<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request, $planId, $type)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $plan = Plan::find($planId);

        if (!$plan) {
            return response()->json(['error' => 'Plan not found.'], 404);
        }

        $price = $type === 'annual' ? $plan->annual_price : $plan->monthly_price;
        $unitAmount = $price * 100; // Make sure this is not zero

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $unitAmount,
                    'product_data' => [
                        'name' => $plan->name . " ($type)",
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['planId' => $planId, 'sub_type' => $type]), // Add 'sub_type' to the route parameters
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request, $planId)
    {
        // Retrieve the subscription type from the request parameters
        $type = strtolower($request->input('sub_type'));

        // Calculate the start and end dates based on the subscription type
        $startDate = Carbon::now();
        $endDate = null;

        if ($type === 'annual') {
            $endDate = $startDate->copy()->addYear();
        } elseif ($type === 'monthly') {
            $endDate = $startDate->copy()->addMonth();
        }

        // Retrieve the authenticated user
        $user = auth()->user();

        // Retrieve the user's subscription
        $subscription = $user->subscription;

        if ($subscription) {
            // Update the existing subscription
            $subscription->plan_id = $planId;
            $subscription->start_date = $startDate;
            $subscription->end_date = $endDate;
            $subscription->save();
        } else {
            // Create a new subscription
            $subscription = new Subscription([
                'plan_id' => $planId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            $user->subscription()->save($subscription);
        }

        // Redirect the user to their profile
        return redirect()->route('user.profile')->with('success', 'Subscription updated successfully.');
    }


    public function cancel()
    {
        return redirect()->route('plans.index')->with('error', 'Payment cancelled.');
    }
}

