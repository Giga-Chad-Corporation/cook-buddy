<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $response = Http::post('/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->ok()) {
            $responseData = $response->json();
            return redirect()->route('home')->with('success', $responseData['message']);
        }

        return redirect()->route('login')->with('error', 'Invalid credentials.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
//            $user->tokens()->delete();
            $user->api_token = null;
            $user->save();
        }

        Auth::logout();

        return redirect()->route('login');
    }


    private function generateRandomDeviceName()
    {
        return 'device_' . Str::random(8);
    }
}
