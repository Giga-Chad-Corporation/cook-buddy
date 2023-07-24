<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MaterialController extends Controller
{
    public function index()
    {
        $items = Item::whereHas('itemType', function ($query) {
            $query->where('type_name', 'material');
        })->get();


        return view('shop.material', compact('items'));
    }
}
