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
            $stats = [
                'employes' => Employe::where('entreprise_id', $user->entreprise_id)->count(),
                'bulletins_mois' => BulletinSalaire::where('entreprise_id', $user->entreprise_id)
                                                  ->whereMonth('date_generation', now()->month)
                                                  ->whereYear('date_generation', now()->year)
                                                  ->count(),
                'salaire_total_mois' => BulletinSalaire::where('entreprise_id', $user->entreprise_id)
                                                       ->whereMonth('date_generation', now()->month)
                                                       ->whereYear('date_generation', now()->year)
                                                       ->sum('salaire_net'),
            ];
            
            $entreprises = collect([$user->entreprise]);
            $recentBulletins = BulletinSalaire::with(['employe', 'entreprise'])
                                             ->where('entreprise_id', $user->entreprise_id)
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
