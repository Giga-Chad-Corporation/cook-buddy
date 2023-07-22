@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Mon Panier</h1>

        <!-- Message container -->
        <div id="message" class="alert d-none"></div>


        <div class="row">
            @foreach($cartItems as $item)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm h-100" style="height:100%;">
                        <img class="card-img-top card-img" src="{{ asset($item->picture_url) }}" alt="Card image cap">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->model_name }}</h5>
                            <!-- Include Quantity -->
                            <p class="card-text">Quantité : {{ $item->quantity }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-item" data-id="{{ $item->id }}">Supprimer</button>

                                    <p class="text-right">{{ number_format($item->selling_price * $item->quantity, 2) }} €</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        document.querySelectorAll('.delete-item').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                let itemId = this.dataset.id;

                // Make a DELETE request to the server to remove the item from the cart.
                fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer {{ Auth::user()->api_token }}', // Include the user's API token
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw response; // If the response is not ok, throw the error
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Handle the response from the server.
                        // Show a success message to the user.
                        let messageElement = document.getElementById('message');
                        messageElement.innerText = data.success;
                        messageElement.classList.remove('d-none', 'alert-danger');
                        messageElement.classList.add('alert-success');

                        // Reload the page to show the updated cart after showing the message
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    })
                    .catch(error => {
                        error.json().then(errorMessage => {
                            // Show error message to the user
                            let messageElement = document.getElementById('message');
                            messageElement.innerText = errorMessage.error;
                            messageElement.classList.remove('d-none', 'alert-success');
                            messageElement.classList.add('alert-danger');

                            // Hide the message after 2 seconds
                            setTimeout(() => {
                                messageElement.classList.add('d-none');
                            }, 2000);
                        });
                    });
            });
        });
    </script>


@endsection
