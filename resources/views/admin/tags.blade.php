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
                                <h4>Liste des tags :</h4>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                    +
                                </button>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">name</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td>
                                            <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Voulez-vous vraiment supprimer cet élément ?');">
                                                    Supprimer
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-primary mt-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateItemModal-{{$tag->id}}">modifier
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal" id="updateItemModal-{{ $tag->id }}" tabindex="-1"
                                         role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier un article</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                            aria-label="Fermer">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.tag.update', $tag->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <!-- Your form inputs here -->
                                                        <!-- For example: -->
                                                        <div class="form-group">
                                                            <label for="tag_name-{{ $tag->id }}">Nom</label>
                                                            <input type="text" class="form-control"
                                                                   id="tag_name-{{ $tag->id }}" name="name"
                                                                   value="{{ $tag->name }}" required>
                                                        </div>
                                                        <!-- Similarly, add other fields and prepopulate them with the existing values -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Fermer
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Modifier</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
                    <h5 class="modal-title">Ajouter un tag</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
