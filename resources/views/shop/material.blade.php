@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Material Shop</h1>

        <div class="row">
            @foreach($items as $item)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->model_name }}</h5>
                            <p class="card-text">{{ $item->selling_price }}$</p>
                            <!-- Add to Cart button -->
{{--                            <a href="{{ route('add.to.cart', $item->id) }}" class="btn btn-primary">Add to Cart</a>--}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
