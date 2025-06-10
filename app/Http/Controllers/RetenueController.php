<?php

namespace App\Http\Controllers;

use App\Models\Retenue;
use App\Models\Employe;
use Illuminate\Http\Request;

class RetenueController extends Controller
{
    public function __construct()
    {
        $this->middleware('responsable_entreprise');
    }

    public function index()
    {
        $query = Retenue::with('employe.entreprise');
        
        if (!auth()->user()->isAdmin()) {
            $accessibleEntreprises = auth()->user()->getAccessibleEntreprises();
            $query->whereHas('employe', function($q) use ($accessibleEntreprises) {
                $q->whereIn('entreprise_id', $accessibleEntreprises);
            });
        }
        
        $retenues = $query->orderBy('annee', 'desc')
                         ->orderBy('mois', 'desc')
                         ->paginate(10);
        return view('retenues.index', compact('retenues'));
    }

    public function create()
    {
        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('retenues.create', compact('employes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'employe_id' => 'required|exists:employes,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2020',
            'type' => 'required|in:cotisation_sociale,impot,avance,autre',
            'obligatoire' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        Retenue::create($validated);

        return redirect()->route('retenues.index')
            ->with('success', 'Retenue créée avec succès.');
    }

    public function show(Retenue $retenue)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($retenue->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $retenue->load('employe.entreprise');
        return view('retenues.show', compact('retenue'));
    }

    public function edit(Retenue $retenue)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($retenue->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('retenues.edit', compact('retenue', 'employes'));
    }

    public function update(Request $request, Retenue $retenue)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($retenue->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'employe_id' => 'required|exists:employes,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2020',
            'type' => 'required|in:cotisation_sociale,impot,avance,autre',
            'obligatoire' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $retenue->update($validated);

        return redirect()->route('retenues.index')
            ->with('success', 'Retenue mise à jour avec succès.');
    }

    public function destroy(Retenue $retenue)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($retenue->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $retenue->delete();

        return redirect()->route('retenues.index')
            ->with('success', 'Retenue supprimée avec succès.');
    }
}
