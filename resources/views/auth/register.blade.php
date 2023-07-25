@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Inscription</div>

                    <div class="card-body">
                        <form id="registerForm">
                            @csrf

                            <div id="error-container">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div id="success-container">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">Pseudo</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">Prénom</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">Nom</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">Addresse</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" required>
                                    @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">Numéro de télépone</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" required>
                                    @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Mot de passe</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirmer le mot de passe</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>




                            <button type="button" id="provider-button">Devenir prestataire</button>

                            <div id="provider-fields-container" style="display: none">
                                <div class="mt-4">
                                    <label for="is_provider" value="{{ __('Register as a Provider') }}" />
                                    <input id="is_provider" class="block mt-1" type="checkbox" name="is_provider" value="1" />
                                </div>

                                <div class="form-group">
                                    <label for="provider_type">Type de prestataire</label>
                                    <select class="form-control" id="provider_type" name="provider_type">
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="document">Ajouter un document</label>
                                    <input type="file" class="form-control-file" id="document" name="document">
                                    @error('document')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        S'inscrire
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function initializeAutocomplete() {
            const addressInput = document.getElementById('address');
            const autocomplete = new google.maps.places.Autocomplete(addressInput);

            autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);
        }



        document.addEventListener('DOMContentLoaded', function() {
            // Load Google Maps API asynchronously
            var script = document.createElement('script');
            script.src = `https://maps.google.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initializeAutocomplete`;
            script.defer = true;
            document.body.appendChild(script);

            // Fetch provider types from API
            fetch('{{ route('api.providerTypes') }}')
                .then(response => response.json())
                .then(data => {
                    const providerTypes = data;
                    const providerTypeDropdown = document.getElementById('provider_type');

                    providerTypes.forEach(providerType => {
                        const option = document.createElement('option');
                        option.value = providerType.id;
                        option.text = providerType.type_name;
                        providerTypeDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.log('Error occurred:', error);
                });

            // Register form submit event handler
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                fetch('{{ route('api.register') }}', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            // Display console errors
                            console.log('Erreur d\'inscription:', data.errors);
                            // Display form validation errors
                            const errorMessages = Object.values(data.errors).flat();
                            const errorContainer = document.getElementById('error-container');
                            errorContainer.innerHTML = ''; // Clear previous errors
                            errorMessages.forEach(message => {
                                const errorElement = document.createElement('div');
                                errorElement.className = 'alert alert-danger'; // add bootstrap class
                                errorElement.textContent = message;
                                errorContainer.appendChild(errorElement);
                            });
                        } else if (data.message) {
                            // Registration successful, display success message
                            const successContainer = document.getElementById('success-container');
                            successContainer.innerHTML = ''; // Clear previous success message
                            const successElement = document.createElement('div');
                            successElement.className = 'alert alert-success'; // add bootstrap class
                            successElement.textContent = data.message;
                            successContainer.appendChild(successElement);

                            // Redirect to login page after 3 seconds
                            setTimeout(function(){
                                window.location.href = '{{ route('login') }}';
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.log('Erreure d\'inscription:', error);
                        // Handle registration error
                    });



            });


            // Provider button click event handler
            document.getElementById('provider-button').addEventListener('click', function() {
                const providerFieldsContainer = document.getElementById('provider-fields-container');
                const isProviderCheckbox = document.getElementById('is_provider');
                const documentUploadField = document.getElementById('document');

                if (providerFieldsContainer.style.display === 'none') {
                    providerFieldsContainer.style.display = 'block';
                    this.textContent = 'Fermer';
                } else {
                    providerFieldsContainer.style.display = 'none';
                    this.textContent = 'Devenir prestataire';
                    isProviderCheckbox.checked = false;
                    documentUploadField.value = '';
                }
            });
        });
    </script>

@endsection
