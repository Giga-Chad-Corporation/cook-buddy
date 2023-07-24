@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Propose un cours à domicile</div>
                    <div class="card-body">
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
{{--                                    @foreach($providers as $provider)--}}
{{--                                        <option value="{{ $provider->id }}">{{ $provider->user->first_name . ' ' . $provider->user->last_name }}</option>--}}
{{--                                    @endforeach--}}


                                </select>
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
    </script>

@endsection
