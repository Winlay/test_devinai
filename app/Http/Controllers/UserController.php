<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $query = User::with(['entreprise', 'entreprises']);
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('entreprise_id')) {
            $query->whereHas('entreprises', function($q) use ($request) {
                $q->where('entreprise_id', $request->entreprise_id);
            });
        }
        
        $users = $query->orderBy('name')->paginate(15);
        $entreprises = Entreprise::orderBy('nom')->get();
        
        return view('users.index', compact('users', 'entreprises'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $entreprises = Entreprise::orderBy('nom')->get();
        return view('users.create', compact('entreprises'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,responsable_entreprise,employe',
            'entreprise_id' => 'nullable|exists:entreprises,id',
            'entreprises' => 'nullable|array',
            'entreprises.*' => 'exists:entreprises,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'entreprise_id' => $validated['entreprise_id'],
        ]);

        if (!empty($validated['entreprises'])) {
            $user->entreprises()->sync($validated['entreprises']);
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $user->load(['entreprise', 'entreprises', 'employe']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $entreprises = Entreprise::orderBy('nom')->get();
        $user->load(['entreprises']);
        
        return view('users.edit', compact('user', 'entreprises'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,responsable_entreprise,employe',
            'entreprise_id' => 'nullable|exists:entreprises,id',
            'entreprises' => 'nullable|array',
            'entreprises.*' => 'exists:entreprises,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'entreprise_id' => $validated['entreprise_id'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->entreprises()->sync($validated['entreprises'] ?? []);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->entreprises()->detach();
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
