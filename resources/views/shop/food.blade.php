@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-5">Commandez nos plats cuisinés</h1>

        <!-- Search input -->
        <input type="text" id="search" name="search" class="form-control mb-3" placeholder="Search by name">

        <!-- Message container -->
        <div id="message" class="alert d-none"></div>

        <!-- Container for the items -->
        <div id="items-container">
            @include('shop.parts.items', ['items' => $items])
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let query = $(this).val();
                $.ajax({
                    url: "{{ route('shop.food') }}",
                    type: "GET",
                    data: { 'search': query },
                    success: function(data) {
                        // Update the items container with the data from the server
                        $('#items-container').html(data);
                    }
                });
            });

            // Add to Cart functionality
            $(document).on('click', '.add-to-cart', function(event) {
                event.preventDefault();

                let itemId = $(this).attr('data-id');
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
