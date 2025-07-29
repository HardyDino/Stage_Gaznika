@extends('layouts.app')

@section('title', 'Historique de Tous les Clients')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h5 mb-0">
                    <i class="fas fa-history text-primary me-2"></i> Historique de Tous les Clients
                </h1>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour aux Clients
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

            <form action="{{ route('clients.listhistorique') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div class="w-100 w-md-50">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher dans l'historique..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <select name="type" class="form-select form-select-sm">
                        <option value="all" {{ request('type') === 'all' ? 'selected' : '' }}>Tous les types</option>
                        <option value="modification" {{ request('type') === 'modification' ? 'selected' : '' }}>Modification</option>
                        <option value="commande" {{ request('type') === 'commande' ? 'selected' : '' }}>Commande</option>
                        <option value="ticket" {{ request('type') === 'ticket' ? 'selected' : '' }}>Ticket</option>
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
                            <th>Client</th>
                            <th>Type</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Administrateur</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $event)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <strong>{{ strtoupper(substr($event->user->name ?? 'N/A', 0, 1)) }}</strong>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                <a href="{{ route('clients.history', $event->user) }}">{{ $event->user->name ?? 'N/A' }}</a>
                                            </div>
                                            <small class="text-muted">{{ $event->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $event->type === 'modification' ? 'bg-primary' : ($event->type === 'commande' ? 'bg-info' : 'bg-warning') }}">
                                        {{ ucfirst($event->type) }}
                                    </span>
                                </td>
                                <td>{{ $event->title }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->admin_name ?? 'Système' }}</td>
                                <td>{{ $event->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun événement trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
                <small class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $histories->firstItem() }} à {{ $histories->lastItem() }} sur {{ $histories->total() }} événements
                </small>
                {{ $histories->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection