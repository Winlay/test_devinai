<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Employe;
use App\Models\BulletinSalaire;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $stats = [];
        
        if ($user->isAdmin()) {
            $stats = [
                'entreprises' => Entreprise::count(),
                'employes' => Employe::count(),
                'bulletins_mois' => BulletinSalaire::whereMonth('date_generation', now()->month)
                                                  ->whereYear('date_generation', now()->year)
                                                  ->count(),
                'salaire_total_mois' => BulletinSalaire::whereMonth('date_generation', now()->month)
                                                       ->whereYear('date_generation', now()->year)
                                                       ->sum('salaire_net'),
            ];
            
            $entreprises = Entreprise::all();
            $recentBulletins = BulletinSalaire::with(['employe', 'entreprise'])
                                             ->latest()
                                             ->take(5)
                                             ->get();
        } elseif ($user->isResponsableEntreprise()) {
            $accessibleEntreprises = $user->getAccessibleEntreprises();
            $stats = [
                'employes' => Employe::whereIn('entreprise_id', $accessibleEntreprises)->count(),
                'bulletins_mois' => BulletinSalaire::whereIn('entreprise_id', $accessibleEntreprises)
                                                  ->whereMonth('date_generation', now()->month)
                                                  ->whereYear('date_generation', now()->year)
                                                  ->count(),
                'salaire_total_mois' => BulletinSalaire::whereIn('entreprise_id', $accessibleEntreprises)
                                                       ->whereMonth('date_generation', now()->month)
                                                       ->whereYear('date_generation', now()->year)
                                                       ->sum('salaire_net'),
            ];
            
            $entreprises = Entreprise::whereIn('id', $accessibleEntreprises)->get();
            $recentBulletins = BulletinSalaire::with(['employe', 'entreprise'])
                                             ->whereIn('entreprise_id', $accessibleEntreprises)
                                             ->latest()
                                             ->take(5)
                                             ->get();
        } else {
            $employe = $user->employe;
            if ($employe) {
                $stats = [
                    'bulletins_total' => BulletinSalaire::where('employe_id', $employe->id)->count(),
                    'dernier_salaire' => BulletinSalaire::where('employe_id', $employe->id)
                                                        ->latest()
                                                        ->value('salaire_net') ?? 0,
                ];
                
                $recentBulletins = BulletinSalaire::with(['employe', 'entreprise'])
                                                 ->where('employe_id', $employe->id)
                                                 ->latest()
                                                 ->take(5)
                                                 ->get();
            } else {
                $stats = [];
                $recentBulletins = collect();
            }
            
            $entreprises = collect();
        }
        
        return view('dashboard', compact('stats', 'entreprises', 'recentBulletins'));
    }
}
