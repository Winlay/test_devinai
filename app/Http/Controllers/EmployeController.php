<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    public function __construct()
    {
        $this->middleware('responsable_entreprise');
    }

    public function index()
    {
        $query = Employe::with('entreprise');
        
        if (!auth()->user()->isAdmin()) {
            $accessibleEntreprises = auth()->user()->getAccessibleEntreprises();
            $query->whereIn('entreprise_id', $accessibleEntreprises);
        }
        
        $employes = $query->paginate(10);
        return view('employes.index', compact('employes'));
    }

    public function create()
    {
        $entreprises = auth()->user()->isAdmin() 
            ? Entreprise::all() 
            : Entreprise::whereIn('id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('employes.create', compact('entreprises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:employes,email',
            'telephone' => 'required|string',
            'matricule' => 'required|string|unique:employes,matricule',
            'date_entree' => 'required|date',
            'entreprise_id' => 'required|exists:entreprises,id',
            'salaire_base' => 'required|numeric|min:0',
            'poste' => 'nullable|string',
            'departement' => 'nullable|string',
        ]);

        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($validated['entreprise_id'])) {
            abort(403, 'Accès non autorisé');
        }

        Employe::create($validated);

        return redirect()->route('employes.index')
            ->with('success', 'Employé créé avec succès.');
    }

    public function show(Employe $employe)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $employe->load(['entreprise', 'elementSalaires', 'absences', 'retenues', 'bulletinSalaires']);
        return view('employes.show', compact('employe'));
    }

    public function edit(Employe $employe)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $entreprises = auth()->user()->isAdmin() 
            ? Entreprise::all() 
            : Entreprise::whereIn('id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('employes.edit', compact('employe', 'entreprises'));
    }

    public function update(Request $request, Employe $employe)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:employes,email,' . $employe->id,
            'telephone' => 'required|string',
            'matricule' => 'required|string|unique:employes,matricule,' . $employe->id,
            'date_entree' => 'required|date',
            'entreprise_id' => 'required|exists:entreprises,id',
            'salaire_base' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,suspendu,demissionne,licencie',
            'poste' => 'nullable|string',
            'departement' => 'nullable|string',
        ]);

        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($validated['entreprise_id'])) {
            abort(403, 'Accès non autorisé');
        }

        $employe->update($validated);

        return redirect()->route('employes.index')
            ->with('success', 'Employé mis à jour avec succès.');
    }

    public function destroy(Employe $employe)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $employe->delete();

        return redirect()->route('employes.index')
            ->with('success', 'Employé supprimé avec succès.');
    }
}
