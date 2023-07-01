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
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/image1.jpg') }}" class="d-block w-100" alt="Image description">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/image2.jpg') }}" class="d-block w-100" alt="Image description">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/image3.jpg') }}" class="d-block w-100" alt="Image description">
            </div>
        </div>
        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- Third Section -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="display-4">Learn More About Us</h2>
                <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
            </div>
        </div>
    </div>
@endsection
