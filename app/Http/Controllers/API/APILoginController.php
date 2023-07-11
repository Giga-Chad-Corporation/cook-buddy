<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Str;

class APILoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Generate and store a new api_token for the authenticated user
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();
            return response()->json(['message' => 'Login successful', 'api_token' => $user->api_token], 200);
        }

        // Authentication failed
        return response()->json(['error' => 'Invalid Credentials'], 401);
    }
    public function loginAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Generate and store a new api_token for the authenticated user
            $user = Auth::user();
            $user->api_token = Str::random(60);
            if ($user->is_admin == '1'){
                $user->save();
                return response()->json(['message' => 'Login successful', 'api_token' => $user->api_token], 200);
            }
            else{
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
            $user->save();

            return response()->json(['message' => 'Login successful', 'api_token' => $user->api_token], 200);
        }

        // Authentication failed
        return response()->json(['error' => 'Invalid Credentials'], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'Logout successful'], 200);
    }
}

