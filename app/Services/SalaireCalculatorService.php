<?php

namespace App\Services;

use App\Models\Employe;
use App\Models\BulletinSalaire;

class SalaireCalculatorService
{
    public function calculerSalaire(Employe $employe, int $mois, int $annee): array
    {
        $salaireBase = $employe->salaire_base;
        
        $totalPrimes = $employe->elementSalaires()
            ->whereIn('type', ['prime', 'indemnite', 'avantage'])
            ->sum('montant');
        
        $totalRetenues = $employe->retenues()
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->sum('montant');
        
        $salaireBrut = $salaireBase + $totalPrimes;
        $salaireNet = $salaireBrut - $totalRetenues;
        
        return [
            'salaire_brut' => $salaireBrut,
            'total_primes' => $totalPrimes,
            'total_retenues' => $totalRetenues,
            'salaire_net' => $salaireNet,
        ];
    }
    
    public function genererBulletin(Employe $employe, int $mois, int $annee): BulletinSalaire
    {
        $calculs = $this->calculerSalaire($employe, $mois, $annee);
        
        return BulletinSalaire::updateOrCreate(
            [
                'employe_id' => $employe->id,
                'mois' => $mois,
                'annee' => $annee,
            ],
            [
                'entreprise_id' => $employe->entreprise_id,
                'salaire_brut' => $calculs['salaire_brut'],
                'total_primes' => $calculs['total_primes'],
                'total_retenues' => $calculs['total_retenues'],
                'salaire_net' => $calculs['salaire_net'],
                'date_generation' => now(),
            ]
        );
    }
}
