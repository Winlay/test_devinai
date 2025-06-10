<?php

namespace App\Http\Controllers;

use App\Models\BulletinSalaire;
use App\Models\Employe;
use App\Services\SalaireCalculatorService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BulletinSalaireController extends Controller
{
    protected $salaireCalculator;

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $query = BulletinSalaire::with(['employe', 'entreprise']);
        
        if (auth()->user()->isEmploye()) {
            $query->whereHas('employe', function($q) {
                $q->where('user_id', auth()->id());
            });
        } elseif (auth()->user()->isResponsableEntreprise()) {
            $accessibleEntreprises = auth()->user()->getAccessibleEntreprises();
            $query->whereIn('entreprise_id', $accessibleEntreprises);
        }
        
        if ($request->filled('entreprise_id') && auth()->user()->isAdmin()) {
            $query->where('entreprise_id', $request->entreprise_id);
        }
        
        if ($request->filled('mois')) {
            $query->where('mois', $request->mois);
        }
        
        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }
        
        $bulletins = $query->orderBy('annee', 'desc')
                          ->orderBy('mois', 'desc')
                          ->paginate(10);
        
        return view('bulletins.index', compact('bulletins'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isResponsableEntreprise()) {
            abort(403, 'Accès non autorisé');
        }

        $employes = auth()->user()->isAdmin() 
            ? Employe::with('entreprise')->get()
            : Employe::whereIn('entreprise_id', auth()->user()->getAccessibleEntreprises())->get();
            
        return view('bulletins.create', compact('employes'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isResponsableEntreprise()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2020',
        ]);

        $employe = Employe::findOrFail($validated['employe_id']);
        
        if (!auth()->user()->isAdmin() && !auth()->user()->hasAccessToEntreprise($employe->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $salaireCalculator = new \App\Services\SalaireCalculatorService();
        $bulletin = $salaireCalculator->genererBulletin(
            $employe, 
            $validated['mois'], 
            $validated['annee']
        );

        return redirect()->route('bulletins.show', $bulletin)
            ->with('success', 'Bulletin de salaire généré avec succès.');
    }

    public function show(BulletinSalaire $bulletin)
    {
        if (auth()->user()->isEmploye()) {
            if (!$bulletin->employe || $bulletin->employe->user_id != auth()->id()) {
                abort(403, 'Accès non autorisé');
            }
        } elseif (auth()->user()->isResponsableEntreprise()) {
            if (!auth()->user()->hasAccessToEntreprise($bulletin->entreprise_id)) {
                abort(403, 'Accès non autorisé');
            }
        }

        $bulletin->load(['employe', 'entreprise']);
        return view('bulletins.show', compact('bulletin'));
    }

    public function pdf(BulletinSalaire $bulletin)
    {
        if (auth()->user()->isEmploye()) {
            if (!$bulletin->employe || $bulletin->employe->user_id != auth()->id()) {
                abort(403, 'Accès non autorisé');
            }
        } elseif (auth()->user()->isResponsableEntreprise()) {
            if (!auth()->user()->hasAccessToEntreprise($bulletin->entreprise_id)) {
                abort(403, 'Accès non autorisé');
            }
        }

        $bulletin->load(['employe', 'entreprise']);
        
        $pdf = Pdf::loadView('bulletins.pdf', compact('bulletin'));
        
        $filename = 'bulletin_' . $bulletin->employe->matricule . '_' . 
                   $bulletin->mois . '_' . $bulletin->annee . '.pdf';
        
        return $pdf->download($filename);
    }

    public function bulkPdf(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isResponsableEntreprise()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2020',
        ]);

        $entreprise = \App\Models\Entreprise::findOrFail($validated['entreprise_id']);
        
        if (auth()->user()->isResponsableEntreprise() && !auth()->user()->hasAccessToEntreprise($entreprise->id)) {
            abort(403, 'Accès non autorisé');
        }

        $bulletins = BulletinSalaire::with(['employe', 'entreprise'])
            ->where('entreprise_id', $validated['entreprise_id'])
            ->where('mois', $validated['mois'])
            ->where('annee', $validated['annee'])
            ->orderBy('employe_id')
            ->get();

        if ($bulletins->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun bulletin trouvé pour cette période.');
        }

        $pdf = Pdf::loadView('bulletins.bulk-pdf', compact('bulletins', 'entreprise'));
        
        $filename = 'bulletins_' . str_replace(' ', '_', strtolower($entreprise->nom)) . '_' . 
                   $validated['mois'] . '_' . $validated['annee'] . '.pdf';
        
        return $pdf->download($filename);
    }

    public function destroy(BulletinSalaire $bulletin)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isResponsableEntreprise()) {
            abort(403, 'Accès non autorisé');
        }

        if (auth()->user()->isResponsableEntreprise() && !auth()->user()->hasAccessToEntreprise($bulletin->entreprise_id)) {
            abort(403, 'Accès non autorisé');
        }

        $bulletin->delete();

        return redirect()->route('bulletins.index')
            ->with('success', 'Bulletin de salaire supprimé avec succès.');
    }
}
