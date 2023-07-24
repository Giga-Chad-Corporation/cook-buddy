@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                        <div id="errorMessages" class="alert alert-danger" style="display: none;"></div>
                        @if ($services->count() > 0)
                            <h2 class="card-title">Cours en ligne :</h2>
                            <ul class="list-group">
                                @foreach ($services as $service)
                                    <li class="list-group-item d-flex">
                                        <div class="col-3 mt-3">
                                            <img src="{{ asset('storage/' . $service->picture) }}" alt="{{ $service->title }}" class="rounded float-left"  style="height: 200px; width: 200px;object-fit: cover;">
                                        </div>
                                        <div class="col-6 text-center pr-3 pl-4 mt-3">
                                            <h4 style="font-weight: bold">{{ $service->title }}</h4>
                                            <p>{{ $service->description }}</p>
                                            <p style="font-size: 14px">Début: {{ \Carbon\Carbon::parse($service->start_date_time)->format('d-m-Y H:i') }}</p>
                                            <p style="font-size: 14px">Fin: {{ \Carbon\Carbon::parse($service->end_date_time)->format('d-m-Y H:i') }}</p>
                                            <p>{{ $service->cost }} €</p>
                                            <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#reservationModal" onclick="setServiceId({{ $service->id }})">Participer</button>
                                        </div>
                                        <div class="col-3 mt-3">
                                            <h6 style="font-weight: bold">Prestataire :</h6>
                                            @if ($service->users->count() > 0)
                                                @foreach ($service->users as $user)
                                                    @if ($user->provider)
                                                        <p>{{ $user->first_name }} {{ $user->last_name }}</p>
                                                        <p>{{ $user->email }}</p>
                                                        @if ($user->phone)
                                                            <p>{{ $user->phone }}</p>
                                                        @endif
                                                        @if ($user->description)
                                                            <p>{{ $user->description }}</p>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                <p>No provider information available.</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Pas de Cours en ligne disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reservationModal" tabindex="-2" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Voulez-vous assister à ce cours en ligne ?</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="button" class="btn btn-primary" id="modalConfirmReservation">Oui</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let serviceId;

        function setServiceId(id) {
            serviceId = id;
        }

        document.getElementById('modalConfirmReservation').addEventListener('click', function() {
            fetch('/service/user/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    service_id: serviceId
                })
            })
                .then(response => {
                    if (response.ok) {
                        const successMessageElement = document.getElementById('successMessage');
                        successMessageElement.textContent = 'Inscription réussie !';
                        successMessageElement.style.display = 'block';

                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else if (response.status === 400) {
                        return response.json();
                    } else {
                        throw new Error('Failed to add service to user.');
                    }
                })
                .then(data => {
                    const errorMessagesElement = document.getElementById('errorMessages');
                    errorMessagesElement.textContent = data.message;
                    errorMessagesElement.style.display = 'block';

                    window.scrollTo({ top: 0, behavior: 'smooth' });

                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    $('#reservationModal').modal('hide');
                });
        });

        $('[data-toggle="modal"]').click(function() {
            $('#reservationModal').modal('show');
        });
    </script>
@endsection
