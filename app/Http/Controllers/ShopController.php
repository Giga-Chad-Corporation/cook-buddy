<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function addToCart($id)
    {
        $item = Item::find($id);

        if (!$item) {
            abort(404);
        }

        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();

        // If no order in cart, create one
        if (!$order) {
            $order = new Order;
            $order->user_id = $user->id;
            $order->status = 'pending';
            $order->save();
        }

        // Get quantity of item in the order
        $quantity = $order->items()->where('item_id', $id)->first()->pivot->quantity ?? 0;

        // Add or increment item in order
        $order->items()->syncWithoutDetaching([$id => ['quantity' => $quantity + 1]]);

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }



    public function showCart()
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

        return view('cart.show', compact('cartItems'));
    }




}
