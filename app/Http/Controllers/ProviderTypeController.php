<?php

namespace App\Http\Controllers;

use App\Models\ProviderType;

class ProviderTypeController extends Controller
{
    public function index()
    {
        $providerTypes = ProviderType::all();
        return response()->json($providerTypes);
    }
}
