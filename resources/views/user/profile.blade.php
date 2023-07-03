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
                                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/user/default-profile-picture.png') }}" alt="Photo de profil" class="img-fluid rounded-circle">
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
                                            <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}">
                                            <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Email :</strong></label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Username :</strong></label>
                                            <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Adresse :</strong></label>
                                            <input type="text" name="address" class="form-control" value="{{ $user->address ?: '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Téléphone :</strong></label>
                                            <input type="text" name="phone" class="form-control" value="{{ $user->phone ?: '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Description :</strong></label>
                                            <input type="text" name="description" class="form-control" value="{{ $user->description ?: '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Mot de passe :</strong> (laissez vide pour ne pas le modifier)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>

                                    </form>
                                    <p class="card-text" id="userRole">Rôle : {{ $role }}</p>
                                    @if($role == 'Provider')
                                        <p class="card-text" id="userProviderType">Type de Prestataire : {{ $providerType }}</p>
                                    @endif
                                    <p id="userName" class="userInfo"><strong>Nom :</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p id="userEmail" class="userInfo"><strong>Email :</strong> {{ $user->email }}</p>
                                    <p id="userUsername" class="userInfo"><strong>Username :</strong> {{ $user->username }}</p>
                                    <p id="userAddress" class="userInfo"><strong>Adresse :</strong> {{ $user->address ?: 'Adresse non fournie' }}</p>
                                    <p id="userPhone" class="userInfo"><strong>Téléphone :</strong> {{ $user->phone ?: 'Téléphone non fourni' }}</p>
                                    <p id="userDescription" class="userInfo"><strong>Description :</strong> {{ $user->description ?: 'Description non fournie' }}</p>
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

            // Afficher l'input de fichier lorsque survol de l'image de profil
            $('#profilePictureContainer').hover(function() {
                $('#changePictureOverlay').fadeIn();
            }, function() {
                $('#changePictureOverlay').fadeOut();
            });

            // Gérer le changement de l'input de fichier
            $('#profilePictureInput').change(function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePictureContainer img').attr('src', e.target.result);
                    $('#saveProfilePicture').show();
                }
                reader.readAsDataURL(this.files[0]);
            });

            // Gérer le clic sur le bouton "Enregistrer la photo de profil"
            $('#saveProfilePicture').click(function() {
                var formData = new FormData();
                var file = $('#profilePictureInput')[0].files[0];
                formData.append('profile_picture', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('user.profile.picture') }}",
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

            // Gérer le clic sur le bouton "Modifier"
            $(document).off('click', '#editButton').on('click', '#editButton', function() {
                $('#editButton').hide();
                $('#saveButton').show();
                $('.userInfo').hide();
                $('#profileForm').show();
            });

            // Gérer la soumission du formulaire
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.profile.update') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log('Success:', data);

                        try {
                            if (data.user) {
                                var user = data.user;
                                $('#userName').text('Nom : ' + user.first_name + ' ' + user.last_name);
                                $('#userEmail').text('Email : ' + user.email);
                                $('#userUsername').text('Username : ' + user.username);
                                $('#userAddress').text('Adresse : ' + user.address);
                                $('#userPhone').text('Téléphone : ' + user.phone);
                                $('#userDescription').text('Description : ' + user.description);

                                // Rafraîchir la page pour afficher les nouvelles informations
                                location.reload();

                                $('#message').text('Profil mis à jour avec succès !').show();
                                setTimeout(function() {
                                    $('#message').fadeOut();
                                }, 3000);
                            } else {
                                console.error('Données de réponse non conformes:', data);
                            }
                        } catch (error) {
                            console.error('Une erreur s\'est produite lors de la mise à jour des données :', error);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log('Error:', thrownError);
                        console.log('XHR:', xhr);
                        console.log('AJAX options:', ajaxOptions);
                    }
                });
            });

            // Afficher le bouton "Modifier" et les informations utilisateur si les champs ne sont pas vides
            if ($('#userName').text().trim().length > 0) {
                $('#editButton').show();
                $('.userInfo').show();
            }
        });
    </script>
@endsection
