<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // Don't forget to import the Log facade

class APILoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed, create a new token for the user
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        // Authentication failed
        return response()->json(['error' => 'Invalid Credentials'], 401);
    }

}
