@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Achetez du matériel de cuisine</h1>

        <div class="row">
            @foreach($items as $item)
                <div class="col-md-4">
                    <div class="card mb-4 h-100">
                    <!-- Add image here -->
                        <img src="{{ $item->picture_url }}" class="card-img-top card-img" alt="{{ $item->model_name }}">

                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <!-- Title at the left below the image -->
                                <h5 class="card-title">{{ $item->model_name }}</h5>
                                <!-- Price at the right below the image -->
                                <p class="card-text">{{ $item->selling_price }}$</p>
                            </div>
                            <!-- Add to Cart button -->
                            {{-- <a href="{{ route('add.to.cart', $item->id) }}" class="btn btn-primary">Add to Cart</a> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
