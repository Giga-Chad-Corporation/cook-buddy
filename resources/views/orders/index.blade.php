@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Mes commandes</h1>

        @foreach($orders as $order)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Commande #{{ $order->id }}</h5>
                    <p class="card-text">Status: payé</p>
                    <p class="card-text">Date de paiment: {{ $order->updated_at }}</p>
                    <p class="card-text">Date de livraison prévue: {{ $order->planned_delivery_date }}</p>
                    <p class="card-text">Adresse de livraison: {{ $order->delivery_address }}</p>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Voir détail</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
