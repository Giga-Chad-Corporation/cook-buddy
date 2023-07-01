<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken($request->device_name)->plainTextToken;

                return response()->json([
                    'message' => 'Login successful.',
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing the request.',
            ], 500);
        }
    }



    public function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new \Exception("The provided credentials are incorrect.");
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 200);
    }
}




