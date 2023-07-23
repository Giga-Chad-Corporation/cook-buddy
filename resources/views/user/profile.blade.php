@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Profil Utilisateur</h1>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="card-text">
                            <div class="row">
                                <div class="col-md-4">
                                    <div id="profilePictureContainer" class="profile-picture-container" style="position: relative; width: 200px; height: 200px;">
                                        <img id="profilePicture" src="" alt="Photo de profil" class="img-fluid">
                                        <div id="changePictureOverlay" class="change-picture-overlay" style="position: absolute; bottom: 0; width: 100%; height: 25%; background-color: rgba(0,0,0,0.5); color: white; display: flex; align-items: center; justify-content: center;">
                                            <label for="profilePictureInput" class="change-picture-label" style="cursor: pointer;">Changer la photo</label>
                                        </div>
                                    </div>
                                    <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
                                    <button id="saveProfilePicture" class="btn btn-primary mt-3" style="display: none; margin-top: 15px;">Enregistrer la photo de profil</button>
                                </div>

                                <div class="col-md-8">
                                    <div class="text-right">
                                        <button id="editButton" class="btn btn-primary">Modifier</button>
                                    </div>
                                    <form id="profileForm" method="POST" style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" id="saveButton" class="btn btn-primary mb-3" style="display: none;">Enregistrer les modifications</button>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Nom :</strong></label>
                                            <input type="text" name="first_name" class="form-control" value="">
                                            <input type="text" name="last_name" class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Email :</strong></label>
                                            <input type="email" name="email" class="form-control" value="" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Username :</strong></label>
                                            <input type="text" name="username" class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Adresse :</strong></label>
                                            <input type="text" name="address" id="autocomplete" class="form-control" placeholder="Enter your address">
                                            <input type="hidden" name="formatted_address" id="formatted_address" value="">
                                        </div>

                                        <div class="form-group">
                                            <label><strong>Téléphone :</strong></label>
                                            <input type="text" name="phone" class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Description :</strong></label>
                                            <input type="text" name="description" class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Mot de passe :</strong> (laissez vide pour ne pas le modifier)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
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
                                    </form>

                                    <p class="card-text" id="userRole"></p>
                                    <p class="card-text" id="userProviderType" style="display: none;"></p>
                                    <p id="userName" class="userInfo"></p>
                                    <p id="userEmail" class="userInfo"></p>
                                    <p id="userUsername" class="userInfo"></p>
                                    <p id="userAddress" class="userInfo"></p>
                                    <p id="userPhone" class="userInfo"></p>
                                    <p id="userDescription" class="userInfo"></p>

                                    <h2>Plan</h2>
                                    <p id="planName"></p>
                                    <p id="startDate"></p>
                                    <p id="endDate"></p>
                                    <p id="isActive"></p>



                                    <a href="{{ route('plans.index') }}" class="btn btn-primary">Changer de Plan</a>

                                    <div id="message" class="alert alert-success" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <h1 class="card-title">A Venir</h1>
                        <div class="card-text">
                            <div class="row ml-5">
                            <div id="services-container"></div></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function initializeAutocomplete() {
            var autocompleteInput = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(autocompleteInput);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                document.getElementById('formatted_address').value = place.address;
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            var profileForm = document.getElementById('profileForm');
            var editButton = document.getElementById('editButton');
            var saveButton = document.getElementById('saveButton');
            var profilePictureInput = document.getElementById('profilePictureInput');

            // Fetch user profile data
            fetch("{{ route('api.user.profile') }}", {
                headers: {
                    'Authorization': 'Bearer {{ auth()->user()->api_token }}',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.user) {
                        var user = data.user;
                        var role = data.role;
                        var providerType = data.providerType;
                        var plan = data.plan;
                        var subscription = data.subscription;


                        var startDate = new Date(subscription.start_date);
                        var endDate = new Date(subscription.end_date);

                        // Update subscription information with formatted dates
                        document.getElementById('planName').innerText = 'Plan : ' + plan.name;
                        document.getElementById('startDate').innerText = 'Date de début : ' + startDate.toLocaleDateString('fr-FR');
                        // Display end date only if the plan is not "Free"
                        if (plan.name !== "Free") {
                            document.getElementById('endDate').innerText = 'End Date : ' + (subscription.end_date ? endDate.toLocaleDateString('fr-FR') : 'N/A');
                        } else {
                            document.getElementById('endDate').style.display = 'none';
                        }
                        document.getElementById('isActive').innerText = 'Status : ' + (subscription.is_active ? 'Active' : 'Inactive');

                        document.getElementById('userName').innerText = 'Nom : ' + user.first_name + ' ' + user.last_name;
                        document.getElementById('userEmail').innerText = 'Email : ' + user.email;
                        document.getElementById('userUsername').innerText = 'Username : ' + user.username;
                        document.getElementById('userAddress').innerText = 'Adresse : ' + user.address;
                        document.getElementById('userPhone').innerText = 'Téléphone : ' + user.phone;
                        document.getElementById('userDescription').innerText = 'Description : ' + user.description;
                        document.getElementById('userRole').innerText = 'Rôle : ' + role;

                        if (role === 'Prestataire') {
                            document.getElementById('userProviderType').innerText = 'Type de Prestataire : ' + providerType;
                            document.getElementById('userProviderType').style.display = 'block';
                        } else {
                            document.getElementById('userProviderType').style.display = 'none';
                        }


                        document.querySelector('input[name="first_name"]').value = user.first_name;
                        document.querySelector('input[name="last_name"]').value = user.value = user.last_name;
                        document.querySelector('input[name="email"]').value = user.email;
                        document.querySelector('input[name="username"]').value = user.username;
                        document.querySelector('input[name="address"]').value = user.address;
                        document.querySelector('input[name="phone"]').value = user.phone;
                        document.querySelector('input[name="description"]').value = user.description;

                        // Set the profile picture
                        document.getElementById('profilePictureContainer').querySelector('img').src = user.profile_photo_path ? '/storage/' + user.profile_photo_path : '/images/user/default-profile-picture.png';

                    } else {
                        console.error('Failed to fetch user profile data:', data);
                    }
                })
                .catch(error => console.log(error));

            // Update user profile picture
            document.getElementById('saveProfilePicture').addEventListener('click', function() {
                profilePictureInput.click(); // Trigger the file input click event
            });

            // Handle file selection
            profilePictureInput.addEventListener('change', function() {
                var formData = new FormData();
                var file = profilePictureInput.files[0];
                formData.append('profile_picture', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch("{{ route('api.user.profile.picture') }}", {
                    headers: {
                        'Authorization': 'Bearer {{ auth()->user()->api_token }}',
                    },
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('saveProfilePicture').style.display = 'none';
                        document.getElementById('message').innerText = 'Photo de profil enregistrée avec succès !';
                        document.getElementById('message').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('message').style.display = 'none';
                        }, 3000);
                        setTimeout(function(){
                            window.location.href = "{{ route('user.profile') }}";
                        }, 2000);
                    })
                    .catch(error => console.log(error));
            });

            // Handle the "Modifier" button click
            editButton.addEventListener('click', function() {
                editButton.style.display = 'none';
                saveButton.style.display = 'block';

                var userInfoElements = document.getElementsByClassName('userInfo');
                for (var i = 0; i < userInfoElements.length; i++) {
                    userInfoElements[i].style.display = 'none';
                }

                profileForm.style.display = 'block';
            });

            // Handle the form submission
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(profileForm);
                formData.append('_method', 'PATCH');
                formData.append('_token', '{{ csrf_token() }}');

                fetch("{{ route('api.user.profile.update') }}", {
                    headers: {
                        'Authorization': 'Bearer {{ auth()->user()->api_token }}',
                    },
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);

                        if (data.errors) {
                            // Display console errors
                            console.log('Profile update failed:', data.errors);
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
                            // Update successful, display success message
                            const successContainer = document.getElementById('success-container');
                            successContainer.innerHTML = ''; // Clear previous success message
                            const successElement = document.createElement('div');
                            successElement.className = 'alert alert-success'; // add bootstrap class
                            successElement.textContent = data.message;
                            successContainer.appendChild(successElement);

                            if (data.user) {

                                console.log('Updated user:', user);
                                var user = data.user;

                                // Update user info on the page
                                document.getElementById('userName').innerText = 'Nom : ' + user.first_name + ' ' + user.last_name;
                                document.getElementById('userEmail').innerText = 'Email : ' + user.email;
                                document.getElementById('userUsername').innerText = 'Username : ' + user.username;
                                document.getElementById('userAddress').innerText = 'Adresse : ' + user.address;
                                document.getElementById('userPhone').innerText = 'Téléphone : ' + user.phone;
                                document.getElementById('userDescription').innerText = 'Description : ' + user.description;

                                // Reset form fields
                                document.querySelector('input[name="first_name"]').value = '';
                                document.querySelector('input[name="last_name"]').value = '';
                                document.querySelector('input[name="email"]').value = '';
                                document.querySelector('input[name="username"]').value = '';
                                document.querySelector('input[name="address"]').value = '';
                                document.querySelector('input[name="phone"]').value = '';
                                document.querySelector('input[name="description"]').value = '';
                            }

                            // Redirect to the user's profile after 3 seconds
                            setTimeout(function() {
                                window.location.href = "{{ route('user.profile') }}";
                            }, 2000);
                        }
                    })
                    .catch(error => console.log('Error:', error));
            });
        });

        // Replace 'api.user.services' with the actual URL of your endpoint if necessary.
        fetch("{{ route('api.user.services') }}", {
            headers: {
                'Authorization': 'Bearer {{ auth()->user()->api_token }}',
            }
        })
            .then(response => response.json())
            .then(data => {
                const services = data.services;
                const servicesContainer = document.getElementById('services-container');

                services.forEach(service => {
                    const serviceElement = document.createElement('div');

                    // Formatting the date and time
                    const startDate = new Date(service.start_date_time);
                    const formattedStartDate = startDate.getDate() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getFullYear().toString().substr(-2) + ' ' + startDate.getHours() + ':' + startDate.getMinutes();

                    const endDate = new Date(service.end_date_time);
                    const formattedEndDate = endDate.getDate() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getFullYear().toString().substr(-2) + ' ' + endDate.getHours() + ':' + endDate.getMinutes();

                    serviceElement.innerHTML = `
                    <h3>${service.title}</h3>
                    <p>Type: ${service.service_type.type_name}</p>
                    <p>Prestataire: ${service.provider.first_name} ${service.provider.last_name}</p>
                    <p>Début: ${formattedStartDate}</p>
                    <p>Fin: ${formattedEndDate}</p>
                    <p>Prix: ${service.cost} €</p>
                `;

                    servicesContainer.appendChild(serviceElement);
                });
            })
            .catch(error => console.error('Error:', error));

        // Load Google Maps API asynchronously
        var script = document.createElement('script');
        script.src = `https://maps.google.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initializeAutocomplete`;
        script.defer = true;
        document.body.appendChild(script);

    </script>
@endsection
