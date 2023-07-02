@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

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
                                <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>

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
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <button type="button" id="provider-button">Register as a Provider</button>

                            <div id="provider-fields-container" style="display: none">
                                <div class="mt-4">
                                    <label for="is_provider" value="{{ __('Register as a Provider') }}" />
                                    <input id="is_provider" class="block mt-1" type="checkbox" name="is_provider" value="1" />
                                </div>

                                <div class="form-group">
                                    <label for="provider_type">Provider Type</label>
                                    <select class="form-control" id="provider_type" name="provider_type">
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="document">Upload Document</label>
                                    <input type="file" class="form-control-file" id="document" name="document">
                                    @error('document')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
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
        document.addEventListener('DOMContentLoaded', function() {
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
                console.log('Form submit event triggered');
                console.log('Sending registration request...');

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
                            console.log('Registration failed:', data.errors);
                            // Display form validation errors
                            const errorMessages = Object.values(data.errors).flat();
                            const errorContainer = document.getElementById('error-container');
                            errorContainer.innerHTML = '';
                            errorMessages.forEach(message => {
                                const errorElement = document.createElement('div');
                                errorElement.textContent = message;
                                errorContainer.appendChild(errorElement);
                            });
                        } else if (data.message) {
                            // Registration successful, display success message
                            const successContainer = document.getElementById('success-container');
                            successContainer.textContent = data.message;
                        }
                    })
                    .catch(error => {
                        console.log('Registration failed:', error);
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
                    this.textContent = 'Unregister as a Provider';
                } else {
                    providerFieldsContainer.style.display = 'none';
                    this.textContent = 'Register as a Provider';
                    isProviderCheckbox.checked = false;
                    documentUploadField.value = '';
                }
            });
        });
    </script>
@endsection
