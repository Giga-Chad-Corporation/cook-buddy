@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Achetez du matériel de cuisine</h1>

        <!-- Message container -->
        <div id="message" class="alert d-none"></div>

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
                                <p class="card-text">{{ $item->selling_price }}€</p>
                            </div>
                            <!-- Add to Cart button -->
                            <button class="btn btn-primary add-to-cart" data-id="{{ $item->id }}">Ajouter au panier</button>
                            <!-- View Item button -->
                            <a href="{{ route('item.show', $item->id) }}" class="btn btn-secondary">Voir l'article</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
        // Add to Cart functionality
        let addToCartButtons = document.querySelectorAll('.add-to-cart');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                let itemId = this.getAttribute('data-id');
                let quantity = 1; // Change this if you want to add quantity selector for each item

                // Make a request to the server to add the item to the cart.
                fetch('/add-to-cart/' + itemId, { // Update the route here
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer {{ Auth::user()->api_token }}', // Include the user's API token
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw response; // If the response is not ok, throw an error
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Handle the response from the server.
                        // Show a success message to the user.
                        let messageElement = document.getElementById('message');
                        messageElement.innerText = data.success;
                        messageElement.classList.remove('d-none');
                        messageElement.classList.add('alert-success');

                        // Hide the message after 2 seconds
                        setTimeout(() => {
                            messageElement.classList.add('d-none');
                        }, 2000);
                    })
                    .catch(error => {
                        error.json().then(errorMessage => {
                            // Show error message to the user
                            let messageElement = document.getElementById('message');
                            messageElement.innerText = errorMessage.error;
                            messageElement.classList.remove('d-none');
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
