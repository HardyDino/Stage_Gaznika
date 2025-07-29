<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->whereHas('role', function ($q) {
            $q->where('name', 'client');
        });

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'actif') {
                $query->where('is_active', true);
            } elseif ($status === 'inactif') {
                $query->where('is_active', false);
            }
        }

        $clients = $query->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function listhistorique(Request $request)
    {
        $query = History::with('user')->whereHas('user', function ($q) {
            $q->whereHas('role', function ($r) {
                $r->where('name', 'client');
            });
        });

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($type = $request->query('type')) {
            if (in_array($type, ['modification', 'commande', 'ticket'])) {
                $query->where('type', $type);
            }
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('clients.listhistorique', compact('histories'));
    }

    public function history(Request $request, User $client)
    {
        if ($client->role->name !== 'client') {
            return redirect()->route('clients.index')->with('error', 'Utilisateur non client.');
        }

        $query = History::where('user_id', $client->id);

        if ($search = $request->query('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        if ($type = $request->query('type')) {
            if (in_array($type, ['modification', 'commande', 'ticket'])) {
                $query->where('type', $type);
            }
        }

        $history = $query->paginate(10);

        return view('clients.history', compact('client', 'history'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => DB::raw("crypt('{$request->password}', gen_salt('bf'))"),
            'role_id' => 2, // Assuming role_id 2 is 'client'
            'is_active' => true,
            'remember_token' => null,
        ]);

        History::create([
            'user_id' => $client->id,
            'type' => 'modification',
            'title' => 'Création du client',
            'description' => "Client {$client->name} créé.",
            'admin_name' => Auth::user()->name ?? 'Système',
            'created_at' => now(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client créé avec succès.');
    }

    public function show(User $client)
    {
        if ($client->role->name !== 'client') {
            return redirect()->route('clients.index')->with('error', 'Utilisateur non client.');
        }
        return view('clients.show', compact('client'));
    }

    public function edit(User $client)
    {
        if ($client->role->name !== 'client') {
            return redirect()->route('clients.index')->with('error', 'Utilisateur non client.');
        }
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, User $client)
    {
        if ($client->role->name !== 'client') {
            return redirect()->route('clients.index')->with('error', 'Utilisateur non client.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $changes = [];
        if ($client->name !== $request->name) {
            $changes[] = ['type' => 'old', 'field' => 'Nom', 'value' => $client->name];
            $changes[] = ['type' => 'new', 'field' => 'Nom', 'value' => $request->name];
        }
        if ($client->email !== $request->email) {
            $changes[] = ['type' => 'old', 'field' => 'Email', 'value' => $client->email];
            $changes[] = ['type' => 'new', 'field' => 'Email', 'value' => $request->email];
        }
        if ($client->phone !== $request->phone) {
            $changes[] = ['type' => 'old', 'field' => 'Téléphone', 'value' => $client->phone ?? 'N/A'];
            $changes[] = ['type' => 'new', 'field' => 'Téléphone', 'value' => $request->phone ?? 'N/A'];
        }

        $client->update($request->only(['name', 'email', 'phone']));

        if (!empty($changes)) {
            History::create([
                'user_id' => $client->id,
                'type' => 'modification',
                'title' => 'Modification du profil',
                'description' => "L'administrateur a modifié les informations du client.",
                'changes' => $changes,
                'admin_name' => Auth::user()->name ?? 'Système',
                'created_at' => now(),
            ]);
        }

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(User $client)
    {
        if ($client->role->name !== 'client') {
            return redirect()->route('clients.index')->with('error', 'Utilisateur non client.');
        }

        $client->delete();
        History::create([
            'user_id' => $client->id,
            'type' => 'modification',
            'title' => 'Suppression du client',
            'description' => "Le client {$client->name} a été supprimé.",
            'admin_name' => Auth::user()->name ?? 'Système',
            'created_at' => now(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
    }

    public function block(Request $request, User $client)
    {
        if ($client->role->name !== 'client') {
            return redirect()->route('clients.index')->with('error', 'Utilisateur non client.');
        }

        $client->update(['is_active' => false]);

        History::create([
            'user_id' => $client->id,
            'type' => 'modification',
            'title' => 'Client bloqué',
            'description' => "Le client {$client->name} a été bloqué.",
            'admin_name' => Auth::user()->name ?? 'Système',
            'created_at' => now(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client bloqué avec succès.');
    }
}