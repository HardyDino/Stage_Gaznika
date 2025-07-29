<!-- resources/views/partials/header.blade.php -->
<header class="navbar navbar-expand navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
        <!-- Search Bar -->
        <div class="d-flex align-items-center w-50 mx-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Rechercher une commande, un client...">
            </div>
        </div>
        
        <!-- User Actions -->
        <div class="ms-auto d-flex align-items-center">
            <button class="btn btn-light position-relative mx-2">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>
            
            <button class="btn btn-light position-relative mx-2">
                <i class="fas fa-envelope"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">1</span>
            </button>
            
            @auth
                <div class="dropdown ms-3">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="ms-2 d-none d-lg-inline">
                            <span class="fw-semibold">{{ Auth::user()->name }}</span><br>
                            <small class="text-muted">{{ Auth::user()->role->name }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a class="btn btn-primary ms-3" href="{{ route('login') }}">Connexion</a>
            @endauth
        </div>
    </div>
</header>