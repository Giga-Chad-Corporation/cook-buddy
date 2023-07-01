<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getToken(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new \Exception("The provided credentials are incorrect.");
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        // Set other fields as necessary

        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }
}
