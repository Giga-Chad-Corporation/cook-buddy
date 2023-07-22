<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::guard("admin")->check()) {
            return view('admin.index');
        }
        elseif (Auth::guard("web")->check()) {
            return redirect('/');
        }
        else {
            return redirect('/login');
        }
    }

    public static function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $admin = Admin::where('email', $credentials['email'])->first();
        $user = $admin->user;

        if (Hash::check($credentials['password'], $admin->password)) {
            $user->api_token = Str::random(60);
            $user->save();

            session(['isAdmin' => true]);

            Auth::guard('admin')->login($admin);

            return redirect('/admin');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

}
