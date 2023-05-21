<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This is where you add the index function:
    public function index()
    {
        return view('home');
    }
}
