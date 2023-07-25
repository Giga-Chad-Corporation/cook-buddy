@extends('layouts.app')

@section('content')

<div class="container-fluid mt-5">
    <div class="max-height">
        <div class="row justify-content-center">
            <div class="col-md-4 formation-block">
                <div class="card ">
                    <a href="{{ route('shop.material') }}">
                        <img src="{{ asset('images/shop/materiel.jpg') }}" alt="Cours à domicile"
                             class="formation-image">
                        <h4>Matériel</h4>
                    </a>
                </div>
            </div>
            <div class="col-md-4 formation-block">
                <div class="card">
                    <a href="{{ route('shop.food') }}">
                        <img src="{{ asset('images/shop/plats.jpg') }}" alt="Leçon en ligne" class="formation-image">
                        <h4>Plats cuisinés</h4>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
