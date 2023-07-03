<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cook Buddy</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Eczar:wght@400;700&display=swap"
          rel="stylesheet">

    <!-- Add your custom CSS here -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image"/>
            CookBuddy
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Acceuil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/training') }}">Formations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/events') }}">Événements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/reservations') }}">Reservations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/store') }}">Boutique</a>
                </li>
            </ul>
        </div>
        <div class="d-flex">
            @guest
                <button class="btn btn-outline-primary me-2" onclick="location.href='{{ route('register') }}'">
                    Inscription
                </button>
                <button class="btn btn-primary ml-2" onclick="location.href='{{ route('login') }}'">Connexion</button>
            @endguest
            @auth
                <button class="btn btn-outline-primary me-2" onclick="location.href='{{ route('user.profile') }}'">
                    Profile
                </button>
                    <button class="btn btn-primary ml-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Déconnexion
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                @endauth
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<!-- Modals -->

<div class="modal fade" id="inscriptionModal" tabindex="-1" aria-labelledby="inscriptionModalLabel" aria-hidden="true">
    <!-- Modal content goes here -->
</div>

<div class="modal fade" id="connexionModal" tabindex="-1" aria-labelledby="connexionModalLabel" aria-hidden="true">
    <!-- Modal content goes here -->
</div>

<!-- Scripts -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let prevScrollPos = window.pageYOffset;
        let navbar = document.querySelector(".navbar");
        let headerHeight = navbar.offsetHeight;

        window.addEventListener("scroll", function () {
            let currentScrollPos = window.pageYOffset;

            if (prevScrollPos > currentScrollPos) {
                navbar.classList.remove("hidden");
            } else if (currentScrollPos > headerHeight) {
                navbar.classList.add("hidden");
            }

            prevScrollPos = currentScrollPos;
        });
    });

    function confirmLogout() {
        if (confirm("Êtes-vous sure de vouloir vous déconnecter ?")) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
</body>
</html>
