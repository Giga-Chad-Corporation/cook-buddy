@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="jumbotron text-center" style="background-image: url('{{ asset('images/back.jpg') }}'); background-size: cover; color: white; height: 100vh; display: flex; flex-direction: column; justify-content: center;">
            <h1 class="display-4">Prenez des cours à domicile par nos meilleurs chefs</h1>
            <form action="{{ route('chef.search') }}" method="get">
                <div class="input-group mx-auto" style="max-width: 400px;">
                    <input type="text" class="form-control" name="location" id="location" placeholder="Enter your location">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>     
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var locationInput = document.getElementById('location');
            var enableLocationBtn = document.getElementById('enableLocationBtn');

            enableLocationBtn.addEventListener('click', function() {
                locationInput.value = 'Latitude, Longitude'; // Replace with actual coordinates
            });
        });
    </script>
@endpush
