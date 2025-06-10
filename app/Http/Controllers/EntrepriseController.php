<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['show']);
    }

    public function index()
    {
        $entreprises = Entreprise::with('employes')->paginate(10);
        return view('entreprises.index', compact('entreprises'));
    }

    public function create()
    {
        return view('entreprises.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ninea' => 'required|string|unique:entreprises,ninea',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:entreprises,email',
            'telephone' => 'required|string',
            'secteur_activite' => 'nullable|string',
            'date_creation' => 'nullable|date',
        ]);

        Entreprise::create($validated);

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise créée avec succès.');
    }

    public function show(Entreprise $entreprise)
    {
        $entreprise->load(['employes', 'bulletinSalaires']);
        return view('entreprises.show', compact('entreprise'));
    }

    public function edit(Entreprise $entreprise)
    {
        return view('entreprises.edit', compact('entreprise'));
    }

    public function update(Request $request, Entreprise $entreprise)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ninea' => 'required|string|unique:entreprises,ninea,' . $entreprise->id,
            'adresse' => 'required|string',
            'email' => 'required|email|unique:entreprises,email,' . $entreprise->id,
            'telephone' => 'required|string',
            'secteur_activite' => 'nullable|string',
            'date_creation' => 'nullable|date',
            'active' => 'boolean',
        ]);

        $entreprise->update($validated);

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise mise à jour avec succès.');
    }

    public function destroy(Entreprise $entreprise)
    {
        $entreprise->delete();

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise supprimée avec succès.');
    }
}
