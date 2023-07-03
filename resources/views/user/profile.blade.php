@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">User Profile</h1>
                        <div class="card-text">
                            <div class="row">
                                <div class="col-md-4">
                                    <div id="profilePictureContainer" class="profile-picture-container" style="position: relative; width: 200px; height: 200px;">
                                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/user/default-profile-picture.png') }}" alt="Profile Picture" class="img-fluid rounded-circle">
                                        <div id="changePictureOverlay" class="change-picture-overlay" style="position: absolute; bottom: 0; width: 100%; height: 25%; background-color: rgba(0,0,0,0.5); color: white; display: flex; align-items: center; justify-content: center;">
                                            <label for="profilePictureInput" class="change-picture-label" style="cursor: pointer;">Change Picture</label>
                                        </div>
                                    </div>
                                    <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
                                    <button id="saveProfilePicture" class="btn btn-primary mt-3" style="display: none; margin-top: 15px;">Save profile picture</button>
                                </div>

                                <div class="col-md-8">
                                    <button id="editButton" class="btn btn-primary">Edit</button>
                                    <form id="profileForm" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit" id="saveButton" class="btn btn-primary mb-3" style="display: none;">Save Changes</button>

                                        <div class="form-group">
                                            <label><strong>Name:</strong></label>
                                            <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}">
                                            <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Email:</strong></label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Username:</strong></label>
                                            <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Address:</strong></label>
                                            <input type="text" name="address" class="form-control" value="{{ $user->address ?: '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Phone:</strong></label>
                                            <input type="text" name="phone" class="form-control" value="{{ $user->phone ?: '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Description:</strong></label>
                                            <input type="text" name="description" class="form-control" value="{{ $user->description ?: '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password:</strong> (leave blank to keep the same)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>

                                    </form>
                                    <p class="card-text" id="userRole">Role: {{ $role }}</p>
                                    @if($role == 'Provider')
                                        <p class="card-text" id="userProviderType">Provider Type: {{ $provider_type }}</p>
                                    @endif
                                    <p id="userName" class="userInfo"><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p id="userEmail" class="userInfo"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p id="userUsername" class="userInfo"><strong>Username:</strong> {{ $user->username }}</p>
                                    <p id="userAddress" class="userInfo"><strong>Address:</strong> {{ $user->address ?: 'Address not provided' }}</p>
                                    <p id="userPhone" class="userInfo"><strong>Phone:</strong> {{ $user->phone ?: 'Phone not provided' }}</p>
                                    <p id="userDescription" class="userInfo"><strong>Description:</strong> {{ $user->description ?: 'Description not provided' }}</p>
                                    <p class="userInfo"><strong>Password:</strong> ********</p>

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

            // Show the file input when hovering over the profile picture
            $('#profilePictureContainer').hover(function() {
                $('#changePictureOverlay').fadeIn();
            }, function() {
                $('#changePictureOverlay').fadeOut();
            });

            // Handle file input change
            $('#profilePictureInput').change(function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePictureContainer img').attr('src', e.target.result);
                    $('#saveProfilePicture').show();
                }
                reader.readAsDataURL(this.files[0]);
            });

            // Handle "Save profile picture" button click
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
                        $('#message').text('Profile picture saved successfully!').show();
                        setTimeout(function() {
                            $('#message').fadeOut();
                        }, 3000);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            });

            // Handle "Edit" button click
            $(document).off('click', '#editButton').on('click', '#editButton', function() {
                $('#editButton').hide();
                $('#saveButton').show();
                $('.userInfo').hide();
                $('#profileForm').show();
            });



            // Handle form submit
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
                            if (data.hasOwnProperty('first_name') && data.hasOwnProperty('last_name') && data.hasOwnProperty('email') && data.hasOwnProperty('username') && data.hasOwnProperty('address') && data.hasOwnProperty('phone') && data.hasOwnProperty('description')) {
                                $('#userName').text('Name: ' + data.first_name + ' ' + data.last_name);
                                $('#userEmail').text('Email: ' + data.email);
                                $('#userUsername').text('Username: ' + data.username);
                                $('#userAddress').text('Address: ' + data.address);
                                $('#userPhone').text('Phone: ' + data.phone);
                                $('#userDescription').text('Description: ' + data.description);

                                console.log('Before form hide and user info show');
                                $('#editButton').show();
                                $('#saveButton').hide();
                                $('.userInfo').show();
                                $('#profileForm').hide();
                                console.log('After form hide and user info show');

                                $('#message').text('Profile updated successfully!').show();
                                setTimeout(function() {
                                    $('#message').fadeOut();
                                }, 3000);
                            } else {
                                console.error('Response data not as expected:', data);
                            }
                        } catch (error) {
                            console.error('An error occurred while updating the data:', error);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log('Error:', thrownError);
                        console.log('XHR:', xhr);
                        console.log('AJAX options:', ajaxOptions);
                    }
                });
            });


            // Show edit button and user information if fields are not empty
            if ($('#userName').text().trim().length > 0) {
                $('#editButton').show();
                $('.userInfo').show();
            }
        });
    </script>


@endsection
