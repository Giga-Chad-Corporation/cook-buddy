<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;

class AdminController extends Controller
{
    public function index()
    {
        session()->forget('error');

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
        session()->forget('error');

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $users = User::all();
            return view('admin.users', compact('users'));
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.users');
        }
    }

    public function createUser(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->username = $request->username;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->is_admin = $this->convertToBoolean($request->is_admin);
            $user->description = $request->description;

            $user->save();

            if ($user->is_admin) {
                $admin = new Admin();
                $admin->user_id = $user->id;
                $admin->email = $request->admin_email;
                $admin->password = Hash::make($request->admin_password);
                $admin->is_super_admin = $this->convertToBoolean($request->is_super_admin);
                $admin->manage_admins = $this->convertToBoolean($request->manage_admins);
                $admin->manage_users = $this->convertToBoolean($request->manage_users);
                $admin->manage_providers = $this->convertToBoolean($request->manage_providers);
                $admin->manage_services = $this->convertToBoolean($request->manage_services);
                $admin->manage_plans = $this->convertToBoolean($request->manage_plans);
                $admin->save();
            }
            return redirect()->route('admin.users');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour créer un nouvel utilisateur.');
            return view('admin.users');
        }
    }

    private function convertToBoolean($value)
    {
        return $value === 'on' ? true : false;
    }

    public function deleteUser(Request $request, int $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $user = User::find($request->id);
            $user->delete();
            return redirect()->route('admin.users');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.users');
        }
    }

}
