<?php

namespace App\Http\Controllers;

use App\Models\ElementSalaire;
use App\Models\Employe;
use Illuminate\Http\Request;

class ElementSalaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('responsable_entreprise');
    }

    public function index()
    {
        $query = ElementSalaire::with('employe.entreprise');
        
        if (!auth()->user()->isAdmin()) {
            $accessibleEntreprises = auth()->user()->getAccessibleEntreprises();
            $query->whereHas('employe', function($q) use ($accessibleEntreprises) {
                $q->whereIn('entreprise_id', $accessibleEntreprises);
            });
        }
        
        $elements = $query->paginate(10);
        return view('element-salaires.index', compact('elements'));
    }

    public function create()
    {
        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('element-salaires.create', compact('employes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:prime,indemnite,avantage',
            'nom' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'employe_id' => 'required|exists:employes,id',
            'recurrent' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        ElementSalaire::create($validated);

        return redirect()->route('element-salaires.index')
            ->with('success', 'Élément de salaire créé avec succès.');
    }

    public function show(ElementSalaire $elementSalaire)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($elementSalaire->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $elementSalaire->load('employe.entreprise');
        return view('element-salaires.show', compact('elementSalaire'));
    }

    public function edit(ElementSalaire $elementSalaire)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($elementSalaire->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('element-salaires.edit', compact('elementSalaire', 'employes'));
    }

    public function update(Request $request, ElementSalaire $elementSalaire)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($elementSalaire->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'type' => 'required|in:prime,indemnite,avantage',
            'nom' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'employe_id' => 'required|exists:employes,id',
            'recurrent' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $elementSalaire->update($validated);

        return redirect()->route('element-salaires.index')
            ->with('success', 'Élément de salaire mis à jour avec succès.');
    }

    public function destroy(ElementSalaire $elementSalaire)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($elementSalaire->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $elementSalaire->delete();

        return redirect()->route('element-salaires.index')
            ->with('success', 'Élément de salaire supprimé avec succès.');
    }
}
