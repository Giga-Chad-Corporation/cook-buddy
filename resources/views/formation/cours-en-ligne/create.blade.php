@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Créer Youtube Live</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data" id="createCoursEnLigneForm">
                            @csrf

                            <input type="hidden" name="service_type_id" value="{{ $serviceType->id }}">

                            <div class="form-group mt-2">
                                <label for="start_date_time">Date et heure de début</label>
                                <input id="start_date_time" type="datetime-local" class="form-control" name="start_date_time" required>
                            </div>

                            <div class="form-group mt-2">
                                <label for="end_date_time">Date et heure de fin</label>
                                <input id="end_date_time" type="datetime-local" class="form-control" name="end_date_time" required>
                            </div>

                            <!-- YouTube Livestream Form -->
                            <div class="form-group">
                                <label for="youtube_title">YouTube Livestream Title</label>
                                <input type="text" name="youtube_title" id="youtube_title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="youtube_description">YouTube Livestream Description</label>
                                <textarea name="youtube_description" id="youtube_description" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="youtube_start_date_time">YouTube Livestream Start Date and Time</label>
                                <input type="datetime-local" name="youtube_start_date_time" id="youtube_start_date_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="youtube_end_date_time">YouTube Livestream End Date and Time</label>
                                <input type="datetime-local" name="youtube_end_date_time" id="youtube_end_date_time" class="form-control" required>
                            </div>
                            <!-- End of YouTube Livestream Form -->

                            <div class="form-group mt-2">
                                <label for="title">Titre</label>
                                <input id="title" type="text" class="form-control" name="title">
                            </div>

                            <div class="form-group mt-2">
                                <label for="description">Description</label>
                                <textarea id="description" class="form-control" name="description"></textarea>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#createCoursEnLigneForm').submit(function (event) {
                event.preventDefault();

                var form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        var broadcastId = response.broadcast_id;
                        var streamId = response.stream_id;

                        // Display the broadcast and stream IDs or perform any other action
                        alert('Livestream created! Broadcast ID: ' + broadcastId + ', Stream ID: ' + streamId);
                    },
                    error: function (xhr) {
                        var errorMessage = xhr.responseJSON.message;
                        alert('Failed to create livestream: ' + errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
