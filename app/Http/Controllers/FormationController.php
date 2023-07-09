<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        // Return the view for the Formation page
        return view('formation');
    }

    public function coursADomicile()
    {
        // Return the view for "Cours à domicile"
        return view('formation.cours-a-domicile');
    }

    public function leconEnLigne()
    {
        // Return the view for "Leçon en ligne"
        return view('formation.lecon-en-ligne');
    }

    public function ateliers()
    {
        // Return the view for "Ateliers"
        return view('formation.ateliers');
    }

    public function formationsProfessionnelles()
    {
        // Return the view for "Formations professionnelles"
        return view('formation.formations-professionnelles');
    }
}
