<nav id="sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{url("/admin")}}">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url("/admin/dashboard")}}">Tableau de bord</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url("/admin/users")}}">Utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url("/admin/tags")}}">Tag</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Boutique
                </a>
                <ul class="dropdown-menu dropdown-menu-end-dark " aria-labelledby="navbarDarkDropdownMenuLink">
                    <li><a class="dropdown-item" href="{{url("/admin/types")}}">Type</a></li>
                    <li><a class="dropdown-item" href="{{url("/admin/items")}}">Article</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
