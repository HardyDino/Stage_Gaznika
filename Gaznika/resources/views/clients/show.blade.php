@extends('layouts.app')

@section('title', 'Détails Client')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h1 class="h5 mb-0">
                <i class="fas fa-user text-primary me-2"></i> Détails de {{ $client->name ?? 'N/A' }}
            </h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $client->id }}</p>
                    <p><strong>Nom:</strong> {{ $client->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
                    <p><strong>Téléphone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                    <p><strong>Rôle:</strong> {{ $client->role->name ?? 'N/A' }}</p>
                    <p><strong>Statut:</strong> {{ $client->status }}</p>
                    <p><strong>Créé le:</strong> {{ $client->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">Éditer</a>
                <a href="{{ route('clients.history', $client) }}" class="btn btn-outline-secondary">Historique</a>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Retour</a>
            </div>
        </div>
    </div>
</div>
@endsection