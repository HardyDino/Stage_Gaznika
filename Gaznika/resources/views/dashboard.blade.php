@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h1 class="h5 mb-0">
                <i class="fas fa-tachometer-alt text-primary me-2"></i> Tableau de Bord
            </h1>
        </div>
        <div class="card-body">
            <h2>Bienvenue, {{ Auth::user()->name }} !</h2>
            <p>Vous êtes connecté en tant qu'administrateur.</p>
            <a href="{{ route('clients.index') }}" class="btn btn-primary">Gérer les utilisateurs</a>
        </div>
    </div>
</div>
@endsection