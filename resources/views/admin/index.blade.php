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
                            Bienvenue sur la page d'administration !
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
