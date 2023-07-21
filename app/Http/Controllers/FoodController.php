<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class FoodController extends Controller
{
    public function index()
    {
        $items = Item::whereHas('itemType', function ($query) {
            $query->where('type_name', 'food');
        })->get();


        return view('shop.food', compact('items'));
    }
}

