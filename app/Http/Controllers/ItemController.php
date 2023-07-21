<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            abort(404);
        }

        return view('shop.item', compact('item'));
    }
}
