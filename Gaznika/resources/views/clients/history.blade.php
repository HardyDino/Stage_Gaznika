@extends('layouts.app')

@section('title', 'Historique Utilisateur')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h5 mb-1">
                        <i class="fas fa-history text-primary me-2"></i> Historique de {{ $client->name ?? 'N/A' }}
                    </h1>
                    <small class="text-muted">ID: {{ $client->id }} | Client depuis: {{ $client->created_at->format('d/m/Y') }}</small>
                </div>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('clients.history', $client) }}" method="GET" class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
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

            <div class="timeline">
                @forelse ($history as $event)
                    <div class="timeline-item">
                        <div class="timeline-badge {{ $event->type === 'modification' ? 'bg-primary-light text-primary' : ($event->type === 'commande' ? 'bg-info-light text-info' : 'bg-warning-light text-warning') }}">
                            <i class="fas fa-{{ $event->type === 'modification' ? 'edit' : ($event->type === 'commande' ? 'shopping-cart' : 'exclamation-triangle') }}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">{{ $event->title }}</h5>
                                        <small class="text-muted">{{ $event->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="card-text text-muted mb-2">{{ $event->description }}</p>
                                    @if ($event->type === 'modification')
                                        <div class="bg-light p-3 rounded mb-2">
                                            @foreach ($event->changes as $change)
                                                <div class="text-{{ $change['type'] === 'old' ? 'danger' : 'success' }}">
                                                    <i class="fas fa-{{ $change['type'] === 'old' ? 'minus-circle' : 'plus-circle' }} me-1"></i>
                                                    {{ $change['field'] }}: {{ $change['value'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted"><i class="fas fa-user me-1"></i> Effectué par: {{ $event->admin_name }}</small>
                                    @elseif ($event->type === 'commande')
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-success bg-opacity-10 text-success">Montant: {{ $event->amount }}</span>
                                            <span class="badge bg-primary bg-opacity-10 text-primary">Livraison: {{ $event->delivery_type }}</span>
                                        </div>
                                    @else
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Statut: {{ $event->status }}</span>
                                            <span class="badge bg-info bg-opacity-10 text-info">Priorité: {{ $event->priority }}</span>
                                        </div>
                                        <small class="text-muted"><i class="fas fa-user-shield me-1"></i> Assigné à: {{ $event->assigned_to }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Aucun événement trouvé.</p>
                @endforelse
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
                <small class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $history->firstItem() }} à {{ $history->lastItem() }} sur {{ $history->total() }} événements
                </small>
                {{ $history->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-badge {
        position: absolute;
        left: 0;
        top: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .bg-primary-light {
        background-color: rgba(27, 129, 173, 0.1);
    }

    .bg-info-light {
        background-color: rgba(84, 160, 255, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(255, 209, 102, 0.1);
    }
</style>
@endsection