<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Employe;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('responsable_entreprise');
    }

    public function index()
    {
        $query = Absence::with('employe.entreprise');
        
        if (!auth()->user()->isAdmin()) {
            $accessibleEntreprises = auth()->user()->getAccessibleEntreprises();
            $query->whereHas('employe', function($q) use ($accessibleEntreprises) {
                $q->whereIn('entreprise_id', $accessibleEntreprises);
            });
        }
        
        $absences = $query->orderBy('date_debut', 'desc')->paginate(10);
        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('absences.create', compact('employes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'type' => 'required|in:conge,maladie,permission,absence_injustifiee',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'nullable|string',
            'justifiee' => 'boolean',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $dateDebut = Carbon::parse($validated['date_debut']);
        $dateFin = Carbon::parse($validated['date_fin']);
        $nombreJours = $dateDebut->diffInDays($dateFin) + 1;

        $validated['nombre_jours'] = $nombreJours;

        Absence::create($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence créée avec succès.');
    }

    public function show(Absence $absence)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($absence->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $absence->load('employe.entreprise');
        return view('absences.show', compact('absence'));
    }

    public function edit(Absence $absence)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($absence->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('absences.edit', compact('absence', 'employes'));
    }

    public function update(Request $request, Absence $absence)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($absence->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'type' => 'required|in:conge,maladie,permission,absence_injustifiee',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'nullable|string',
            'justifiee' => 'boolean',
            'statut' => 'required|in:en_attente,approuvee,refusee',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $dateDebut = Carbon::parse($validated['date_debut']);
        $dateFin = Carbon::parse($validated['date_fin']);
        $nombreJours = $dateDebut->diffInDays($dateFin) + 1;

        $validated['nombre_jours'] = $nombreJours;

        $absence->update($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence mise à jour avec succès.');
    }

    public function destroy(Absence $absence)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($absence->employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $absence->delete();

        return redirect()->route('absences.index')
            ->with('success', 'Absence supprimée avec succès.');
    }
}
