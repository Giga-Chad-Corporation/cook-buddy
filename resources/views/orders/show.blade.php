@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Commande #{{ $order->id }}</h1>
        <p>Status: payé</p>
        <p>Date de paiment: {{ $order->updated_at }}</p>
        <p>Date de livraison prévue: {{ $order->planned_delivery_date }}</p>
        <p>Adresse de livraison: {{ $order->delivery_address }}</p>

        <h2 class="mt-5">Articles:</h2>
        <ul>
            @foreach($items as $item)
                <li>{{ $item->model_name }} (Quantité: {{ $item->quantity }})</li>
            @endforeach
        </ul>
    </div>
@endsection
