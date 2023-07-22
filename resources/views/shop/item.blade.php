@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-5 mb-5">
            <!-- Left Side - Image and Title -->
            <div class="col-md-6">
                <h1 class="mb-5">{{ $item->model_name }}</h1>
                <img src="{{ $item->picture_url }}" class="img-fluid" alt="{{ $item->model_name }}">
            </div>

            <!-- Right Side - Description, Price, and Add to Cart Button -->
            <div class="col-md-6">
                <!-- Description -->
                <p class="card-text">{{ $item->description }}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <!-- Price -->
                    <h5>{{ $item->selling_price }}€</h5>

                    <!-- Quantity and Add to Cart button -->
                    <div>
                        <div class="d-flex align-items-center">
                            <!-- Quantity Buttons -->
                            <button id="decrement-quantity" class="btn btn-light">-</button>
                            <input type="number" id="quantity" min="1" value="1" class="form-control" style="width: 70px;">
                            <button id="increment-quantity" class="btn btn-light">+</button>

                            <!-- Add to Cart button -->
                            <button id="add-to-cart" class="btn btn-primary ml-2">Ajouter au panier</button>
                        </div>

                        <!-- Message container -->
                        <div id="message" class="alert d-none mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quantity increment and decrement
        document.getElementById('increment-quantity').addEventListener('click', function() {
            document.getElementById('quantity').value = parseInt(document.getElementById('quantity').value) + 1;
        });

        document.getElementById('decrement-quantity').addEventListener('click', function() {
            if (document.getElementById('quantity').value > 1) {
                document.getElementById('quantity').value = parseInt(document.getElementById('quantity').value) - 1;
            }
        });

        // Add to Cart functionality
        document.getElementById('add-to-cart').addEventListener('click', function(event) {
            event.preventDefault();

            let quantity = document.getElementById('quantity').value;

            // Make a request to the server to add the item to the cart.
            fetch('{{ route('add.to.cart', $item->id) }}', {
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
                        throw response;
                    }
                    return response.json();
                })
                .then(data => {
                    // Show a success message
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
                        // Show error message
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

    </script>
@endsection
