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
                                <h4>Liste des articles :</h4>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                    +
                                </button>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">prix de vente</th>
                                    <th scope="col">nom du type</th>
                                    <th scope="col">Tag</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($articles as $article)
                                    <tr>
                                        <td>{{ $article->id }}</td>
                                        <td>{{ $article->model_name }}</td>
                                        <td>{{ $article->selling_price }}</td>
                                        <td>{{  $items_types->find($article->item_type_id)->type_name}}</td>
                                        <td>
                                            @foreach ($article->tags as $tag)
                                                {{ $tag->name }}<br>
                                            @endforeach
                                        </td>


                                        <td>
                                            <form action="{{ route('admin.items.destroy', $article->id) }}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Voulez-vous vraiment supprimer cet élément ?');">
                                                    Supprimer
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-primary mt-1" data-bs-toggle="modal"
                                                    data-bs-target="#updateItemModal-{{$article->id}}">modifier
                                            </button>

                                        </td>
                                        <td>


                                        </td>
                                    </tr>
                                    <div class="modal" id="updateItemModal-{{ $article->id }}" tabindex="-1"
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
                                                <form action="{{ route('admin.items.update', $article->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <!-- Your form inputs here -->
                                                        <!-- For example: -->
                                                        <div class="form-group">
                                                            <label for="model_name-{{ $article->id }}">Nom</label>
                                                            <input type="text" class="form-control"
                                                                   id="model_name-{{ $article->id }}" name="model_name"
                                                                   value="{{ $article->model_name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="selling_price-{{ $article->id }}">Prix</label>
                                                            <input type="number" class="form-control"
                                                                   id="selling_price-{{ $article->id }}"
                                                                   name="selling_price"
                                                                   value="{{ $article->selling_price }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="itemType-{{ $article->id }}">Nom Du Type</label>
                                                            <select class="form-control"
                                                                    id="itemType-{{ $article->id }}" name="itemType">
                                                                @foreach ($items_types as $items_type)
                                                                    <option
                                                                        value="{{ $items_type->id }}">{{ $items_type->type_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tag_id-{{ $article->id }}">Nom Du Tag</label>
                                                            <select class="form-control"
                                                                    id="tag_id-{{ $article->id }}" name="tag_id">
                                                                @foreach ($tags as $tag)
                                                                    <option
                                                                        value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                                @endforeach

                                                            </select>
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
                    <h5 class="modal-title">Ajouter un article</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.items.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="model_name">Nom</label>
                            <input type="text" class="form-control" id="model_name" name="model_name" required>
                        </div>
                        <div class="form-group">
                            <label for="selling_price">prix de vente</label>
                            <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                        </div>
                        <div class="form-group">
                            <label for="itemType">Nom Du Type</label>
                            <select class="form-control" id="itemType" name="itemType">
                                @foreach ($items_types as $items_type)
                                    <option value="{{ $items_type->id }}">{{ $items_type->type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tag_id">Tag</label>
                            <select class="form-control" id="tag_id" name="tag_id">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
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

@endsection
