<?php

namespace App\Http\Controllers;

use App\Models\Admin;
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

        $admin = Admin::where('email', $credentials['email'])->exists();

        if ($admin) {
            return AdminController::login($request);
        }

        $user = Auth::guard('web')->attempt($credentials);
        if ($user) {
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->api_token) {
                $user->api_token = null;
                $user->save();
            }

            Auth::logout();
        }

        session()->flush();

        return redirect('/login');
    }



}
