<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; min-height: 100vh;">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="120" height="70" style="margin-left: 18%"  class="me-2">
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} text-white">
                <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
            </a>
        </li>

<!-- Clients Section -->
        <div class="mb-4">
            <h6 class="px-3 text-uppercase text-white-50 fw-bold small mb-2">👥 Clients</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }} text-white">
                        <i class="fas fa-user-cog me-2"></i> Gestion utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clients.listhistorique') }}" class="nav-link {{ request()->routeIs('clients.listhistorique') ? 'active' : '' }} text-white">
                        <i class="fas fa-history me-2"></i> Historique
                    </a>
                </li>
            </ul>
        </div>

        <!-- Commandes Section -->
        <div class="mb-4">
            <h6 class="px-3 text-uppercase text-white-50 fw-bold small mb-2">📦 Commandes</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('orders.track') }}" class="nav-link {{ request()->routeIs('orders.track') ? 'active' : '' }} text-white">
                        <i class="fas fa-truck me-2"></i> Suivi des commandes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('orders.manage') }}" class="nav-link {{ request()->routeIs('orders.manage') ? 'active' : '' }} text-white">
                        <i class="fas fa-cog me-2"></i> Gestion des commandes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('orders.geolocation') }}" class="nav-link {{ request()->routeIs('orders.geolocation') ? 'active' : '' }} text-white">
                        <i class="fas fa-map-marker-alt me-2"></i> Géolocalisation
                    </a>
                </li>
            </ul>
        </div>

        <!-- Collections Section -->
        <div class="mb-4">
            <h6 class="px-3 text-uppercase text-white-50 fw-bold small mb-2">♻️ Collectes</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('collections.points') }}" class="nav-link {{ request()->routeIs('collections.points') ? 'active' : '' }} text-white">
                        <i class="fas fa-recycle me-2"></i> Points de collecte
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('collections.schedule') }}" class="nav-link {{ request()->routeIs('collections.schedule') ? 'active' : '' }} text-white">
                        <i class="fas fa-calendar-alt me-2"></i> Planification
                    </a>
                </li>
            </ul>
        </div>

        <!-- Analytics Section -->
        <div class="mb-4">
            <h6 class="px-3 text-uppercase text-white-50 fw-bold small mb-2">📊 Analytics</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('analytics.stats') }}" class="nav-link {{ request()->routeIs('analytics.stats') ? 'active' : '' }} text-white">
                        <i class="fas fa-chart-bar me-2"></i> Statistiques
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('analytics.ai') }}" class="nav-link {{ request()->routeIs('analytics.ai') ? 'active' : '' }} text-white">
                        <i class="fas fa-brain me-2"></i> Insights IA
                    </a>
                </li>
            </ul>
        </div>

        <!-- IoT Section -->
        <div class="mb-4">
            <h6 class="px-3 text-uppercase text-white-50 fw-bold small mb-2">🌐 IoT</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('iot.digesters') }}" class="nav-link {{ request()->routeIs('iot.digesters') ? 'active' : '' }} text-white">
                        <i class="fas fa-microchip me-2"></i> Digesteurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('iot.maintenance') }}" class="nav-link {{ request()->routeIs('iot.maintenance') ? 'active' : '' }} text-white">
                        <i class="fas fa-tools me-2"></i> Maintenance
                    </a>
                </li>
            </ul>
        </div>
    </ul>
</div>