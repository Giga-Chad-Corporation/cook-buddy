<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Article indisponible'], 404);
        }

        // Get the quantity value from the request, use 1 as a default if not provided
        $itemQuantity = $request->input('quantity', 1);

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
        $order->items()->syncWithoutDetaching([$id => ['quantity' => $quantity + $itemQuantity]]);

        // Return a JSON response for the fetch() API in your JavaScript
        return response()->json(['success' => 'Article ajouté au panier !'], 200);
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

    public function removeFromCart($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order) {
            return response()->json(['error' => 'No active cart found'], 404);
        }

        // Get the item to be removed from the order
        $item = $order->items()->where('item_id', $id)->first();

        if (!$item) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        // Check the quantity of the item in the order
        $quantity = $order->items()->where('item_id', $id)->first()->pivot->quantity;

        if ($quantity > 1) {
            // If the quantity is more than 1, decrease it by 1
            $order->items()->updateExistingPivot($id, ['quantity' => $quantity - 1]);
        } else {
            // If the quantity is 1 or less, detach the item from the order
            $order->items()->detach($id);
        }

        if ($quantity <= 1) {
            $order->items()->detach($id);
            return response()->json(['success' => 'Article supprimé'], 200);
        }

        return response()->json(['success' => 'Quantité mise à jour'], 200);
    }


}
