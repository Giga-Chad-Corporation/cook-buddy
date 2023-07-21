@extends('layouts.app')

@section('content')
    <!-- First Section -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="{{ asset('images/image2.jpg') }}" alt="Image description">
            </div>
            <div class="col-lg-6">
                <h2 class="display-4">Bienvenue sur Cook Buddy !</h2>
                <p class="lead">Envie de vous former professionnellement à la cuisine ? Ou alors vous souhaitez simplement apprendre de nouvelles façons amusantes de préparer vos plats préférés ? Quelle que soit votre envie, cet endroit est fait pour vous ! Après tout, le savoir manger n’est-il pas une passion commune à tous ?
                    C’est ainsi que nous voyons le monde de la cuisine chez Cook Master. Voilà pourquoi nous vous offrons, au travers de Cook Buddy, une multitude de services, témoins de notre savoir, notre envie de partage et surtout de notre passion ! </p>
            </div>
        </div>
    </div>

    <!-- Second Section -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-4">Nos formations</h2>
                <p class="lead">Que vous préfériez apprendre dans le confort de votre maison, en ligne à votre rythme, ou parmi des professionnels, nous avons une formation pour vous :</p>
                <ul>
                    <li>Cours à domicile : Nos chefs viennent directement chez vous pour vous enseigner l'art de la cuisine.</li>
                    <li>Cours en ligne : Connectez-vous à nos cours en ligne et apprenez à votre rythme.</li>
                    <li>Formations professionnelles : Parfait pour ceux qui cherchent à faire carrière dans la cuisine.</li>
                    <li>Ateliers : Venez sur place apprendre à confectionner des plats.</li>
                </ul>
                <a class="btn btn-primary" href="{{ url('/formation') }}">Découvrez nos formations</a>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/image3.jpg') }}" alt="Image description">
            </div>
        </div>
    </div>

    <!-- Third Section -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="display-4">Rejoignez Cook Buddy</h2>
                <p class="lead">Inscrivez-vous en tant que prestataire de service ou client et commencez à explorer le monde passionnant de la cuisine avec Cook Buddy.</p>
                <a class="btn btn-primary" href="{{ route('register') }}">Inscription</a>
            </div>
        </div>
    </div>
@endsection
