@extends('layouts.app')

@section('title', 'Gestion des Clients')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h5 mb-0">
                    <i class="fas fa-users text-primary me-2"></i> Gestion des Clients
                </h1>
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Nouveau Client
                </a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('clients.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div class="w-100 w-md-50">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                        <option value="actif" {{ request('status') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('status') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email/Téléphone</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <strong>{{ strtoupper(substr($client->name ?? 'N/A', 0, 1)) }}</strong>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $client->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $client->role->name ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $client->email ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $client->phone ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $client->role->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $client->is_active ? 'bg-success' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $client->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ $client->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-success" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clients.block', $client) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Bloquer">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('clients.history', $client) }}" class="btn btn-sm btn-outline-secondary" title="Historique">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun client trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
                <small class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $clients->firstItem() }} à {{ $clients->lastItem() }} sur {{ $clients->total() }} clients
                </small>
                {{ $clients->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection