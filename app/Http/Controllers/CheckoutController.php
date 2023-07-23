<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orderId = DB::table('orders')
            ->where('user_id', $user->id)
            ->where('status', 'pending') // Assuming that 'pending' status means the order is still in cart
            ->pluck('id')
            ->first();

        if ($orderId) {
            $cartItems = DB::table('order_item')
                ->where('order_id', $orderId)
                ->join('items', 'order_item.item_id', '=', 'items.id')
                ->select('items.*', 'order_item.quantity') // Get the quantity of each item
                ->get();
        } else {
            $cartItems = collect(); // return empty collection if no pending order found
        }

        // Calculate totalAmount
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->selling_price * $item->quantity;
        });

        // Fetch all providers who have availability in the future
        $providers = Provider::with(['regions' => function ($query) {
            $query->wherePivot('available_date', '>=', now()->format('Y-m-d'))
                ->orderBy('pivot_available_date')
                ->orderBy('pivot_start_time');
        }])->get();

        $timeSlots = [];
        foreach ($providers as $provider) {
            foreach ($provider->regions as $region) {
                $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $region->pivot->available_date . ' ' . $region->pivot->start_time);
                $endTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $region->pivot->available_date . ' ' . $region->pivot->end_time);
                while ($startTime->lte($endTime)) {
                    // Check if the provider has a service at this time
                    $serviceAtThisTime = $provider->services()->where(function($query) use ($startTime) {
                        $query->whereBetween('start_date_time', [$startTime, $startTime->copy()->addHour()])
                            ->orWhereBetween('end_date_time', [$startTime, $startTime->copy()->addHour()]);
                    })->exists();

                    if (!$serviceAtThisTime) {
                        $timeSlots[$provider->id][] = $startTime->format('Y-m-d H:i:s');
                    }
                    $startTime->addHour();
                }
            }
        }

        return view('cart.checkout', compact('cartItems', 'totalAmount', 'providers', 'timeSlots'));
    }


    public function store(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order) {
            return redirect()->route('cart.show')->with('error', 'No active cart found');
        }

        // Save delivery address
        $order->delivery_address = $validatedData['address'];
        $order->status = 'processing'; // Change the status to 'processing' after checkout
        $order->save();

        // Redirect to payment gateway or order confirmation page
        return redirect()->route('order.confirmation');
    }

    // CheckoutController.php

    public function redirectToStripe(Request $request)
    {
        // Validation
        $request->validate([
            'delivery-address' => 'required|string|max:255',
            'delivery-date' => 'required|date_format:Y-m-d H:i:s',
            'totalAmount' => 'required|numeric',
        ]);

        // Get the current user's pending order
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        // Update the order with the selected delivery details and status
        $order->planned_delivery_date = $request->input('delivery-date');
        $order->delivery_address = $request->input('delivery-address');
        $order->status = 'paid'; // Update status to 'paid'
        $order->save();

        Stripe::setApiKey(config('services.stripe.secret'));

        $totalAmount = $request->input('totalAmount');

        if (!$totalAmount) {
            return response()->json(['error' => 'Invalid total amount.'], 404);
        }

        $unitAmount = $totalAmount * 100; // Convert to cents

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $unitAmount,
                    'product_data' => [
                        'name' => 'Order Payment',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('cart.show'),
            'cancel_url' => route('cart.show'),
        ]);


        $providerId = $request->input('provider-id'); // get the provider id from the form submission

        // Find the provider
        $provider = Provider::find($providerId);

        // Create a service
        $service = new Service;
        $service->service_type_id = 6;
        $service->start_date_time = \Carbon\Carbon::parse($request->input('delivery-date'))->subHour();
        $service->end_date_time = \Carbon\Carbon::parse($request->input('delivery-date'));
        $service->title = "Livraison " . $user->last_name . " " . $user->first_name;
        $service->description = "";
        $service->number_places = 1;
        $service->picture = "";
        $service->cost = 15; // Set any cost you want for the delivery service
        $service->save();

        if ($provider) {
            // Attach the provider to the service
            $service->providers()->attach($provider->id, ['commission' => 5, 'created_at' => now(), 'updated_at' => now()]);

        }

        return redirect()->away($session->url);

    }

}
