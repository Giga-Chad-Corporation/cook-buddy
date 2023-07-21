<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            // Generate and store a new api_token for the authenticated user
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();
            // Authentication passed...
            return redirect('/');
        }

        // Authentication failed...
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        // Remove the api_token for the authenticated user
        $user = Auth::user();
        $user->api_token = null;
        $user->save();
        Auth::logout();

        return redirect('/login');
    }

}
