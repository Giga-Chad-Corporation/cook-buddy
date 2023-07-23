@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5 ">
                    <div class="card-header">Ajouter une disponibilité:</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('provider.availability.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="region">Choisissez une région:</label>
                                <select id="region" name="region" class="form-control">
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mt-2">
                                <label for="date">Choisissez une date:</label>
                                <input type="date" id="date" name="date" class="form-control">
                            </div>

                            <div class="form-group mt-2">
                                <label for="start_time">Heure de début:</label>
                                <input type="time" id="start_time" name="start_time" class="form-control">
                            </div>

                            <div class="form-group mt-2">
                                <label for="end_time">Heure de fin:</label>
                                <input type="time" id="end_time" name="end_time" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Sauvegarder la disponibilité</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Vos disponibilités:</div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($availabilities as $availability)
                                <li class="list-group-item">
                                    <div class="availability d-flex justify-content-between align-items-start" data-id="{{ $availability->id }}">
                                        <div class="info"> <!-- New div -->
                                            <strong>Région:</strong> <span class="region">{{ $availability->name }}</span><br>
                                            <strong>Date:</strong> <span class="date">{{ $availability->pivot->available_date }}</span><br>
                                            <strong>Heure de début:</strong> <span class="start_time">{{ $availability->pivot->start_time }}</span><br>
                                            <strong>Heure de fin:</strong> <span class="end_time">{{ $availability->pivot->end_time }}</span>
                                        </div>

                                        <div class="d-flex flex-column">
                                            <button class="btn btn-primary btn-edit mb-2">Modifier</button>
                                            <button class="btn btn-danger btn-delete">Supprimer</button>
                                        </div>
                                    </div>

                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.availability').forEach(function(availability) {
                var id = availability.getAttribute('data-id');
                var info = availability.querySelector('.info');
                var region = info.querySelector('.region');
                var date = info.querySelector('.date');
                var start_time = info.querySelector('.start_time');
                var end_time = info.querySelector('.end_time');
                var btn_edit = availability.querySelector('.btn-edit');
                var btn_delete = availability.querySelector('.btn-delete');
                var buttonContainer = availability.querySelector('.d-flex.flex-column');

                btn_edit.addEventListener('click', function() {
                    var region_input = document.createElement('input');
                    region_input.type = 'text';
                    region_input.value = region.textContent;
                    region.textContent = '';
                    region.appendChild(region_input);

                    var date_input = document.createElement('input');
                    date_input.type = 'date';
                    date_input.value = date.textContent;
                    date.textContent = '';
                    date.appendChild(date_input);

                    var start_time_input = document.createElement('input');
                    start_time_input.type = 'time';
                    start_time_input.value = start_time.textContent;
                    start_time.textContent = '';
                    start_time.appendChild(start_time_input);

                    var end_time_input = document.createElement('input');
                    end_time_input.type = 'time';
                    end_time_input.value = end_time.textContent;
                    end_time.textContent = '';
                    end_time.appendChild(end_time_input);

                    var btn_save = document.createElement('button');
                    btn_save.textContent = 'Sauvegarder';
                    btn_save.classList.add('btn', 'btn-primary', 'btn-save');

                    btn_save.addEventListener('click', function() {
                        var new_region = region_input.value;
                        var new_date = date_input.value;
                        var new_start_time = start_time_input.value;
                        var new_end_time = end_time_input.value;

                        fetch('/provider/availability/' + id + '/edit', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                region: new_region,
                                date: new_date,
                                start_time: new_start_time,
                                end_time: new_end_time
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                region.textContent = new_region;
                                date.textContent = new_date;
                                start_time.textContent = new_start_time;
                                end_time.textContent = new_end_time;

                                buttonContainer.removeChild(btn_save);
                                buttonContainer.appendChild(btn_edit);
                                buttonContainer.appendChild(btn_delete);
                            });
                    });

                    buttonContainer.appendChild(btn_save);
                    buttonContainer.removeChild(btn_edit);
                    buttonContainer.removeChild(btn_delete);
                });

                btn_delete.addEventListener('click', function() {
                    fetch('/provider/availability/' + id + '/delete', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            availability.parentNode.removeChild(availability);
                        });
                });
            });
        });
    </script>
    
@endsection
