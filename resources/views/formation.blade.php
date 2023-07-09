@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="max-height">
            <div class="row justify-content-center">
                <div class="col-md-3 formation-block">
                    <div class="card ">
                    <a href="{{ route('formation.cours-a-domicile') }}">
                        <img src="{{ asset('images/cooking-class.jpg') }}" alt="Cours à domicile"
                             class="formation-image">
                        <h4>Cours à domicile</h4>
                    </a>
                    </div>
                </div>
                <div class="col-md-3 formation-block">
                    <div class="card">
                    <a href="{{ route('formation.lecon-en-ligne') }}">
                        <img src="{{ asset('images/enligne.jpg') }}" alt="Leçon en ligne" class="formation-image">
                        <h4>Leçon en ligne</h4>
                    </a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-3 formation-block">
                    <div class="card">
                    <a href="{{ route('formation.ateliers') }}">
                        <img src="{{ asset('images/18-min.png') }}" alt="Ateliers" class="formation-image">
                        <h4>Ateliers</h4>
                    </a>
                    </div>
                </div>
                <div class="col-md-3 formation-block">
                    <div class="card">
                    <a href="{{ route('formation.formations-professionnelles') }}">
                        <img src="{{ asset('images/formation-management-cuisine.jpg') }}"
                             alt="Formations professionnelles" class="formation-image">
                        <h4>Formations professionnelles</h4>
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
