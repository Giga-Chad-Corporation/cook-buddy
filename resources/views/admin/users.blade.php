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
                                                <form action="{{ echo '/admin/users?user_id=' . $user->id }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
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
@endsection
