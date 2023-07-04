@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Profil Utilisateur</h1>
                        <div class="card-text">
                            <div class="row">
                                <div class="col-md-4">
                                    <div id="profilePictureContainer" class="profile-picture-container" style="position: relative; width: 200px; height: 200px;">
                                        <img src="{{ isset($user['profile_photo_path']) ? asset('storage/' . $user['profile_photo_path']) : asset('images/user/default-profile-picture.png') }}" alt="Photo de profil" class="img-fluid rounded-circle">
                                        <div id="changePictureOverlay" class="change-picture-overlay" style="position: absolute; bottom: 0; width: 100%; height: 25%; background-color: rgba(0,0,0,0.5); color: white; display: flex; align-items: center; justify-content: center;">
                                            <label for="profilePictureInput" class="change-picture-label" style="cursor: pointer;">Changer la photo</label>
                                        </div>
                                    </div>
                                    <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
                                    <button id="saveProfilePicture" class="btn btn-primary mt-3" style="display: none; margin-top: 15px;">Enregistrer la photo de profil</button>
                                </div>

                                <div class="col-md-8">
                                    <button id="editButton" class="btn btn-primary">Modifier</button>
                                    <form id="profileForm" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit" id="saveButton" class="btn btn-primary mb-3" style="display: none;">Enregistrer les modifications</button>

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
                                            <input type="text" name="address" class="form-control" value="">
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
                                    </form>

                                    <p class="card-text" id="userRole"></p>
                                    <p class="card-text" id="userProviderType" style="display: none;"></p>
                                    <p id="userName" class="userInfo"><strong>Nom :</strong></p>
                                    <p id="userEmail" class="userInfo"><strong>Email :</strong></p>
                                    <p id="userUsername" class="userInfo"><strong>Username :</strong></p>
                                    <p id="userAddress" class="userInfo"><strong>Adresse :</strong></p>
                                    <p id="userPhone" class="userInfo"><strong>Téléphone :</strong></p>
                                    <p id="userDescription" class="userInfo"><strong>Description :</strong></p>
                                    <p class="userInfo"><strong>Mot de passe :</strong> ********</p>

                                    <div id="message" class="alert alert-success" style="display: none;"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Fetch user profile data
            $.ajax({
                url: "{{ route('api.user.profile') }}",
                method: 'GET',
                success: function(data) {
                    if (data.user) {
                        var user = data.user;
                        var role = data.role;
                        var providerType = data.providerType;

                        $('#userName').text('Nom : ' + user.first_name + ' ' + user.last_name);
                        $('#userEmail').text('Email : ' + user.email);
                        $('#userUsername').text('Username : ' + user.username);
                        $('#userAddress').text('Adresse : ' + user.address);
                        $('#userPhone').text('Téléphone : ' + user.phone);
                        $('#userDescription').text('Description : ' + user.description);
                        $('#userRole').text('Rôle : ' + role); // Update the role text

                        if (role === 'Prestataire') {
                            $('#userProviderType').text('Type de Prestataire : ' + providerType); // Update the provider type text
                            $('#userProviderType').show(); // Show provider type only if user role is 'Prestataire'
                        } else {
                            $('#userProviderType').hide(); // Hide provider type for other roles
                        }

                        // Update form fields with user profile data
                        $('input[name="first_name"]').val(user.first_name);
                        $('input[name="last_name"]').val(user.last_name);
                        $('input[name="email"]').val(user.email);
                        $('input[name="username"]').val(user.username);
                        $('input[name="address"]').val(user.address);
                        $('input[name="phone"]').val(user.phone);
                        $('input[name="description"]').val(user.description);
                    } else {
                        console.error('Failed to fetch user profile data:', data);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log('Error:', thrownError);
                }
            });

            // Update user profile picture
            $('#saveProfilePicture').click(function() {
                var formData = new FormData();
                var file = $('#profilePictureInput')[0].files[0];
                formData.append('profile_picture', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('api.user.profile.picture') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#saveProfilePicture').hide();
                        $('#message').text('Photo de profil enregistrée avec succès !').show();
                        setTimeout(function() {
                            $('#message').fadeOut();
                        }, 3000);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            });

            // Handle the "Modifier" button click
            $('#editButton').click(function() {
                $('#editButton').hide();
                $('#saveButton').show();
                $('.userInfo').hide();
                $('#profileForm').show();
            });

            // Handle the form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    type: "POST",
                    url: "{{ route('api.user.profile.update') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log('Success:', data);

                        if (data.user) {
                            var user = data.user;

                            // Update user info on the page
                            $('#userName').text('Nom : ' + user.first_name + ' ' + user.last_name);
                            $('#userEmail').text('Email : ' + user.email);
                            $('#userUsername').text('Username : ' + user.username);
                            $('#userAddress').text('Adresse : ' + user.address);
                            $('#userPhone').text('Téléphone : ' + user.phone);
                            $('#userDescription').text('Description : ' + user.description);

                            // Reset form fields
                            $('input[name="first_name"]').val('');
                            $('input[name="last_name"]').val('');
                            $('input[name="email"]').val('');
                            $('input[name="username"]').val('');
                            $('input[name="address"]').val('');
                            $('input[name="phone"]').val('');
                            $('input[name="description"]').val('');

                            // Hide form, show user info and "Modifier" button
                            $('#profileForm').hide();
                            $('.userInfo').show();
                            $('#saveButton').hide();
                            $('#editButton').show();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log('Error:', thrownError);
                    }
                });
            });
        });
    </script>

@endsection
