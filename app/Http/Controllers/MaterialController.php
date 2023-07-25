<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        $query->whereHas('itemType', function ($query) {
            $query->where('type_name', 'material');
        });

        if ($request->has('search')) {
            $query->where('model_name', 'LIKE', '%' . $request->get('search') . '%');
        }

        $items = $query->get();

        if ($request->ajax()) {
            return view('shop.parts.items', compact('items'))->render();
        }

        return view('shop.material', compact('items'));
    }
}
