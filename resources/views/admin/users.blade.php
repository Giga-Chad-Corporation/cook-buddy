@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-2"> {{-- Colonne pour la navbar --}}
                @include('admin.sidebar')
            </div>
            <div class="col-md-10"> {{-- Colonne pour le contenu --}}
                <div class="card">
                    <div class="card-header">Page d'administration</div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @else
                            Utilisateurs de la plateforme :

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Rôle</th>
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
                // Si l'utilisateur confirme, soumettez le formulaire de suppression
                document.getElementById('deleteForm' + userId).submit();
            }
        }
    </script>

@endsection
