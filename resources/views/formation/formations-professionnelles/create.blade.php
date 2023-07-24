@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Proposer une formation professionelle</div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="service_type_id" value="{{ $serviceType->id }}">

                            <div class="form-group mt-2">
                                <label for="start_date_time">Date et heure de début</label>
                                <input id="start_date_time" type="datetime-local" class="form-control" name="start_date_time" required>
                            </div>

                            <div class="form-group mt-2">
                                <label for="end_date_time">Date et heure de fin</label>
                                <input id="end_date_time" type="datetime-local" class="form-control" name="end_date_time">
                            </div>

                            <div class="form-group mt-2">
                                <label for="title">Titre</label>
                                <input id="title" type="text" class="form-control" name="title">
                            </div>

                            <div class="form-group mt-2">
                                <label for="description">Description</label>
                                <textarea id="description" class="form-control" name="description"></textarea>
                            </div>

                            <div class="form-group mt-2">
                                <label for="provider_id">Provider</label>
                                <select id="provider_id" class="form-control" name="provider_id" required>
                                    <option value="">Choisir un prestataire</option>
                                </select>
                            </div>

                            <div class="form-group mt-2">
                                <label for="building">Bâtiment</label>
                                <select id="building" class="form-control" name="building" required>
                                    <option value="">Sélectionner un bâtiment</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" data-rooms="{{ json_encode($building->rooms) }}">{{ $building->name }} - {{ $building->address }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mt-2">
                                <label for="room">Salle</label>
                                <select id="room" class="form-control" name="room" required>
                                    <option value="">Sélectionner une salle</option>
                                </select>
                            </div>

                            <!-- New hidden input field for the room_id -->
                            <input type="hidden" id="room_id" name="room_id" value="">

                            <div class="form-group mt-2">
                                <label for="number_places">Nombre de places</label>
                                <input id="number_places" type="number" class="form-control" name="number_places" value="1">
                            </div>

                            <div class="form-group">
                                <label for="cost">Prix</label>
                                <input id="cost" type="number" class="form-control" name="cost" step="0.01" min="0.00" required>
                            </div>

                            <div class="form-group mt-2">
                                <label for="picture">Image</label>
                                <input type="file" id="picture" name="picture">
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Créer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Populate the rooms select based on the selected building
        function populateRooms() {
            var buildingSelect = document.getElementById('building');
            var roomSelect = document.getElementById('room');
            var selectedBuildingId = buildingSelect.value;

            // Fetch the available rooms
            fetch(`/get-available-rooms?building_id=${selectedBuildingId}`)
                .then(response => response.json())
                .then(rooms => {
                    // Clear the rooms select
                    roomSelect.innerHTML = '<option value="">Sélectionner une salle</option>';

                    // If rooms data is available, populate the rooms select
                    if (rooms) {
                        rooms.forEach(function(room) {
                            var option = document.createElement('option');
                            option.value = room.id;
                            option.text = room.name;
                            roomSelect.appendChild(option);
                        });
                    }
                });
        }


        // Event listener for building selection change
        document.getElementById('building').addEventListener('change', populateRooms);

        const startDateTimeField = document.getElementById('start_date_time');
        const endDateTimeField = document.getElementById('end_date_time');
        const providerField = document.getElementById('provider_id');

        // When the date/time fields change, fetch the available providers
        startDateTimeField.addEventListener('change', fetchAvailableProviders);
        endDateTimeField.addEventListener('change', fetchAvailableProviders);

        function fetchAvailableProviders() {
            const startDateTime = startDateTimeField.value;
            const endDateTime = endDateTimeField.value;

            if (startDateTime && endDateTime) {
                fetch('/get-available-providers?start_date_time=' + startDateTime + '&end_date_time=' + endDateTime)
                    .then(response => response.json())
                    .then(providers => {
                        // Clear the current provider options
                        while (providerField.firstChild) {
                            providerField.removeChild(providerField.firstChild);
                        }

                        // Add a default option
                        const defaultOption = document.createElement('option');
                        defaultOption.textContent = 'Choisir un prestataire';
                        defaultOption.value = '';
                        providerField.appendChild(defaultOption);

                        // Add the new provider options
                        providers.forEach(provider => {
                            const option = document.createElement('option');
                            option.textContent = provider.user.first_name + ' ' + provider.user.last_name;
                            option.value = provider.id;
                            providerField.appendChild(option);
                        });
                    });
            }
        }

        const buildingField = document.querySelector('#building');
        const roomField = document.querySelector('#room');
        const numberPlacesField = document.querySelector('#number_places');

        buildingField.addEventListener('change', populateRooms);
        roomField.addEventListener('change', fetchRoomDetails);

        function fetchRoomDetails() {
            const roomId = roomField.value;
            // Reference to the hidden room_id input field
            const roomIdField = document.querySelector('#room_id');

            fetch('/get-room-details?room_id=' + roomId)
                .then(response => response.json())
                .then(room => {
                    // Update the max number of places
                    numberPlacesField.setAttribute('max', room.max_capacity);

                    // Update the hidden room_id input field
                    roomIdField.value = room.id;
                });
        }

        // Validation for the number of places
        numberPlacesField.addEventListener('change', validateNumberOfPlaces);

        function validateNumberOfPlaces() {
            const maxPlaces = numberPlacesField.getAttribute('max');
            const currentPlaces = numberPlacesField.value;

            if (parseInt(currentPlaces) > parseInt(maxPlaces)) {
                alert('Le nombre de places que vous avez fournis ne peut pas excéder la capacité maximum de la salle : ' + maxPlaces + '.');
                numberPlacesField.value = maxPlaces; // Resets the value to the maximum allowed
            }
        }

    </script>
@endsection
