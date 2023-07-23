@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Finaliser votre commande</h1>

        <!-- Delivery address input -->
        <div class="mb-3">
            <label for="delivery-address" class="form-label">Adresse de livraison</label>
            <input type="text" class="form-control" id="delivery-address" placeholder="Saisissez votre adresse de livraison">
        </div>

        <!-- Delivery date & time input -->
        <div class="mb-3">
            <label for="delivery-date" class="form-label">Date et heure de livraison</label>
            <select class="form-control" id="delivery-date">
                @foreach($timeSlots as $providerId => $slots)
                    @foreach($slots as $slot)
                        <option value="{{ json_encode(['timeslot' => $slot, 'provider_id' => $providerId]) }}">
                            {{ $slot }}
                        </option>
                    @endforeach
                @endforeach

            </select>
        </div>






        <!-- Order summary -->
        <div class="mb-3">
            <h4>Résumé de la commande</h4>
            <!-- List of cart items -->
            <ul class="list-group mb-3">
                @foreach($cartItems as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <!-- Product Image -->
                            <img src="{{ $item->picture_url }}" class="img-thumbnail mr-3" style="width: 50px; height: 50px;">
                            <div>
                                <h6 class="my-0">{{ $item->model_name }}</h6>
                                <small class="text-muted">Quantité : {{ $item->quantity }}</small>
                            </div>
                        </div>
                        <span class="text-muted">{{ number_format($item->selling_price * $item->quantity, 2) }} €</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Total (EUR)</strong>
                    <strong>{{ number_format($totalAmount, 2) }} €</strong>
                </li>
            </ul>
            <form action="{{ route('checkout.redirectToStripe') }}" method="POST">
                @csrf
                <input type="hidden" name="delivery-address" id="hidden-delivery-address">
                <input type="hidden" name="delivery-date" id="hidden-delivery-date">
                <input type="hidden" name="provider-id" id="hidden-provider-id">
                <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">
                <button type="submit" class="btn btn-primary mt-4">Procéder au paiement</button>
            </form>
        </div>
    </div>

    <!-- Google Places API script -->
    <script>
        function initializeAutocomplete() {
            var input = document.getElementById('delivery-address');
            new google.maps.places.Autocomplete(input);
        }

        // Load Google Maps API asynchronously
        var script = document.createElement('script');
        script.src = `https://maps.google.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initializeAutocomplete`;
        script.defer = true;
        document.body.appendChild(script);

        document.querySelector('form').addEventListener('submit', function(event) {
            document.getElementById('hidden-delivery-address').value = document.getElementById('delivery-address').value;
            document.getElementById('hidden-delivery-date').value = document.getElementById('delivery-date').value;
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            var selectedSlot = JSON.parse(document.getElementById('delivery-date').value);

            document.getElementById('hidden-delivery-date').value = selectedSlot.timeslot;
            document.getElementById('hidden-provider-id').value = selectedSlot.provider_id; // make sure you have this hidden input in your form
            document.getElementById('hidden-delivery-address').value = document.getElementById('delivery-address').value;
        });

    </script>
@endsection
