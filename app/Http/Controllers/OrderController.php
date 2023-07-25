<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()->where('status', 'paid')->get();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order)
    {
        // Check if the order belongs to the logged-in user
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        // Fetch items in the order
        $items = DB::table('order_item')
            ->where('order_id', $order->id)
            ->join('items', 'order_item.item_id', '=', 'items.id')
            ->select('items.*', 'order_item.quantity') // Get the quantity of each item
            ->get();

        return view('orders.show', compact('order', 'items'));
    }

}
