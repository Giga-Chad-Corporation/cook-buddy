@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Creer une formation professionnelle :</div>
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

            // Clear the rooms select
            roomSelect.innerHTML = '<option value="">Sélectionner une salle</option>';

            // Find the selected building option
            var selectedBuildingOption = buildingSelect.options[buildingSelect.selectedIndex];

            // Retrieve the rooms data from the data-rooms attribute
            var roomsData = selectedBuildingOption.getAttribute('data-rooms');

            // If rooms data is available, populate the rooms select
            if (roomsData) {
                var rooms = JSON.parse(roomsData);
                rooms.forEach(function(room) {
                    var option = document.createElement('option');
                    option.value = room.id;
                    option.text = room.name;
                    roomSelect.appendChild(option);
                });
            }
        }

        // Event listener for building selection change
        document.getElementById('building').addEventListener('change', populateRooms);
    </script>
@endsection
