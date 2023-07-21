@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mon Panier</h1>

        <div class="row">
            @foreach($cartItems as $item)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm" style="height:100%;">
                        <img class="card-img-top" src="{{ asset($item->picture_url) }}" alt="Card image cap" style="height:200px; object-fit:cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->model_name }}</h5>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                                    </div>
                                    <p class="text-right">{{ number_format($item->selling_price, 2) }} €</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
