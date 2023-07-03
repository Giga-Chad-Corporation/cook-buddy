<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // User is authenticated, redirect to their profile or dashboard
            return redirect()->route('user.profile');
        } else {
            // User is not authenticated, show the home page
            return view('home');
        }
    }
}

