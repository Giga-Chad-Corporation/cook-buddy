@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-2">
                @include('admin.sidebar')
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Page d'administration</div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @else
                            <div class="d-flex justify-content-between mb-3">
                                <h4>Liste des utilisateurs :</h4>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    +
                                </button>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Email</th>
                                        <th scope="col">Prénom</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->last_name }}</td>

                                            <td>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" id="deleteForm{{ $user->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un utilisateur</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">Prénom</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Nom</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Numéro de téléphone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div id="userDetails" style="display: none;">
                            <div class="form-group mb-2 mt-3">
                                <label for="is_admin">Administrateur</label>
                                <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
                            </div>
                            <div id="adminDetails" style="display: none; border: 1px solid black; border-radius: 6px; padding: 8px">
                                <div class="form-group mb-3 mt-1">
                                    <label for="admin_email">Email Admin</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email">
                                </div>
                                <div class="form-group mb-3 mt-1">
                                    <label for="admin_password">Mot de Passe Admin</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password">
                                </div>
                                <div class="form-group mb-3 mt-1">
                                    <label for="super">Super Admin</label>
                                    <input type="checkbox" class="form-check-input" id="super" name="super">
                                </div>
                                Gestion :
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="form-group">
                                        <label for="manage_admins">Admins</label>
                                        <input type="checkbox" class="form-check-input" id="manage_admins" name="manage_admins">
                                    </div>
                                    <div class="form-group">
                                        <label for="manage_users">Utilisateurs</label>
                                        <input type="checkbox" class="form-check-input" id="manage_users" name="manage_users">
                                    </div>
                                    <div class="form-grou">
                                        <label for="manage_providers">Prestataires</label>
                                        <input type="checkbox" class="form-check-input" id="manage_providers" name="manage_providers">
                                    </div>
                                    <div class="form-group">
                                        <label for="manage_services">Services</label>
                                        <input type="checkbox" class="form-check-input" id="manage_services" name="manage_services">
                                    </div>
                                    <div class="form-group">
                                        <label for="manage_plans">Abonnements</label>
                                        <input type="checkbox" class="form-check-input" id="manage_plans" name="manage_plans">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="container-fluid">
                            <div class="row justify-content-between">
                                <div class="col">
                                    <button type="button" class="btn btn-primary" id="showDetailsBtn">Afficher les détails</button>
                                    <button type="button" class="btn btn-primary" id="hideDetailsBtn" style="display: none;">Masquer les détails</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function confirmDelete(userId) {
            if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
                document.getElementById('deleteForm' + userId).submit();
            }
        }

        $(document).ready(function () {
            $('#showDetailsBtn').click(function () {
                $('#userDetails').show();
                $(this).hide();
                $('#hideDetailsBtn').show();
            });

            $('#hideDetailsBtn').click(function () {
                $('#userDetails').hide();
                $(this).hide();
                $('#showDetailsBtn').show();
            });
        });

        function toggleAdminDetails() {
            var adminDetailsSection = document.getElementById('adminDetails');
            var isAdminCheckbox = document.getElementById('is_admin');

            if (isAdminCheckbox.checked) {
                adminDetailsSection.style.display = 'block';
            } else {
                adminDetailsSection.style.display = 'none';
            }
        }
        document.getElementById('is_admin').addEventListener('change', toggleAdminDetails);
        toggleAdminDetails();

        function superAdminChecked() {
            var superAdminCheckbox = document.getElementById('super');

            var manageAdminsCheckbox = document.getElementById('manage_admins');
            var manageUsersCheckbox = document.getElementById('manage_users');
            var manageProvidersCheckbox = document.getElementById('manage_providers');
            var manageServicesCheckbox = document.getElementById('manage_services');
            var managePlansCheckbox = document.getElementById('manage_plans');

            if (superAdminCheckbox.checked) {
                manageAdminsCheckbox.checked = true;
                manageUsersCheckbox.checked = true;
                manageProvidersCheckbox.checked = true;
                manageServicesCheckbox.checked = true;
                managePlansCheckbox.checked = true;

                manageAdminsCheckbox.disabled = true;
                manageUsersCheckbox.disabled = true;
                manageProvidersCheckbox.disabled = true;
                manageServicesCheckbox.disabled = true;
                managePlansCheckbox.disabled = true;
            } else {
                manageAdminsCheckbox.disabled = false;
                manageUsersCheckbox.disabled = false;
                manageProvidersCheckbox.disabled = false;
                manageServicesCheckbox.disabled = false;
                managePlansCheckbox.disabled = false;
            }
        }
        document.getElementById('super').addEventListener('change', superAdminChecked);
    </script>

@endsection
