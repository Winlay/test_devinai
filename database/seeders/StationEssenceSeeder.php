<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entreprise;
use App\Models\Employe;
use App\Models\ElementSalaire;
use App\Models\BulletinSalaire;
use App\Models\User;
use App\Services\SalaireCalculatorService;

class StationEssenceSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            [
                'nom' => 'Station Total Dakar Centre',
                'ninea' => '2025001234567',
                'adresse' => 'Avenue Léopold Sédar Senghor, Dakar',
                'telephone' => '+221 33 821 1234',
                'email' => 'contact@total-dakar-centre.sn'
            ],
            [
                'nom' => 'Station Shell Almadies',
                'ninea' => '2025001234568',
                'adresse' => 'Route des Almadies, Ngor',
                'telephone' => '+221 33 820 5678',
                'email' => 'info@shell-almadies.sn'
            ],
            [
                'nom' => 'Station Oryx Parcelles Assainies',
                'ninea' => '2025001234569',
                'adresse' => 'Unité 25, Parcelles Assainies',
                'telephone' => '+221 33 835 9012',
                'email' => 'direction@oryx-pa.sn'
            ],
            [
                'nom' => 'Station Elton Rufisque',
                'ninea' => '2025001234570',
                'adresse' => 'Route Nationale 1, Rufisque',
                'telephone' => '+221 33 836 3456',
                'email' => 'contact@elton-rufisque.sn'
            ],
            [
                'nom' => 'Station Sahel Thiaroye',
                'ninea' => '2025001234571',
                'adresse' => 'Thiaroye sur Mer, Pikine',
                'telephone' => '+221 33 834 7890',
                'email' => 'admin@sahel-thiaroye.sn'
            ],
            [
                'nom' => 'Station Petrosen Guédiawaye',
                'ninea' => '2025001234572',
                'adresse' => 'Route de Guédiawaye, Sam Notaire',
                'telephone' => '+221 33 855 2345',
                'email' => 'gestion@petrosen-guediawaye.sn'
            ],
            [
                'nom' => 'Station Vivo Energy Mbao',
                'ninea' => '2025001234573',
                'adresse' => 'Autoroute à péage, Mbao',
                'telephone' => '+221 33 832 6789',
                'email' => 'contact@vivo-mbao.sn'
            ],
            [
                'nom' => 'Station Ola Energy Keur Massar',
                'ninea' => '2025001234574',
                'adresse' => 'Keur Massar, Route de Malika',
                'telephone' => '+221 33 858 0123',
                'email' => 'info@ola-keurmassar.sn'
            ],
            [
                'nom' => 'Station Corlay Liberté 6',
                'ninea' => '2025001234575',
                'adresse' => 'Liberté 6 Extension, Dakar',
                'telephone' => '+221 33 824 4567',
                'email' => 'direction@corlay-liberte6.sn'
            ],
            [
                'nom' => 'Station Touba Oil Yeumbeul',
                'ninea' => '2025001234576',
                'adresse' => 'Yeumbeul Nord, Pikine',
                'telephone' => '+221 33 857 8901',
                'email' => 'admin@toubaoil-yeumbeul.sn'
            ]
        ];

        $postes = [
            ['nom' => 'Gérant', 'salaire_base' => 800000],
            ['nom' => 'Sous-gérant', 'salaire_base' => 600000],
            ['nom' => 'Pompiste Senior', 'salaire_base' => 350000],
            ['nom' => 'Pompiste', 'salaire_base' => 280000],
            ['nom' => 'Caissier Principal', 'salaire_base' => 320000],
            ['nom' => 'Caissier', 'salaire_base' => 250000],
            ['nom' => 'Boutiquier', 'salaire_base' => 300000],
            ['nom' => 'Responsable Lavage', 'salaire_base' => 380000],
            ['nom' => 'Agent Lavage', 'salaire_base' => 220000],
            ['nom' => 'Mécanicien', 'salaire_base' => 450000],
            ['nom' => 'Gardien de Nuit', 'salaire_base' => 200000],
            ['nom' => 'Agent de Sécurité', 'salaire_base' => 240000],
            ['nom' => 'Nettoyeur', 'salaire_base' => 180000],
            ['nom' => 'Magasinier', 'salaire_base' => 290000],
            ['nom' => 'Comptable', 'salaire_base' => 500000],
            ['nom' => 'Secrétaire', 'salaire_base' => 270000],
            ['nom' => 'Chauffeur Livreur', 'salaire_base' => 310000],
            ['nom' => 'Agent Commercial', 'salaire_base' => 330000],
            ['nom' => 'Superviseur', 'salaire_base' => 420000],
            ['nom' => 'Agent Maintenance', 'salaire_base' => 260000]
        ];

        $prenoms = [
            'Amadou', 'Fatou', 'Moussa', 'Aïssatou', 'Ousmane', 'Mariama', 'Ibrahima', 'Khady',
            'Mamadou', 'Awa', 'Cheikh', 'Ndeye', 'Abdoulaye', 'Bineta', 'Modou', 'Astou',
            'Babacar', 'Coumba', 'Alioune', 'Dieynaba', 'Saliou', 'Rokhaya', 'Pape', 'Mame',
            'Lamine', 'Adama', 'Serigne', 'Ndèye', 'Fallou', 'Yacine'
        ];

        $noms = [
            'Diallo', 'Ndiaye', 'Fall', 'Sow', 'Ba', 'Diop', 'Sarr', 'Faye', 'Gueye', 'Sy',
            'Cisse', 'Diouf', 'Kane', 'Mbaye', 'Thiam', 'Seck', 'Ndour', 'Tall', 'Wade', 'Samb',
            'Drame', 'Toure', 'Camara', 'Traore', 'Kone', 'Keita', 'Sidibe', 'Coulibaly', 'Bah', 'Barry'
        ];

        foreach ($stations as $index => $stationData) {
            $entreprise = Entreprise::create($stationData);

            $responsable = User::create([
                'name' => $prenoms[array_rand($prenoms)] . ' ' . $noms[array_rand($noms)],
                'email' => 'responsable' . ($index + 1) . '@station.sn',
                'password' => bcrypt('password'),
                'role' => 'responsable_entreprise',
                'entreprise_id' => $entreprise->id,
            ]);

            for ($i = 0; $i < 20; $i++) {
                $poste = $postes[$i];
                $prenom = $prenoms[array_rand($prenoms)];
                $nom = $noms[array_rand($noms)];
                
                $user = User::create([
                    'name' => $prenom . ' ' . $nom,
                    'email' => strtolower($prenom . '.' . $nom . '.' . ($index + 1) . '.' . ($i + 1) . '@station.sn'),
                    'password' => bcrypt('password'),
                    'role' => 'employe',
                    'entreprise_id' => $entreprise->id,
                ]);

                $employe = Employe::create([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $user->email,
                    'telephone' => '+221 7' . rand(0, 9) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'matricule' => 'ST' . ($index + 1) . sprintf('%03d', $i + 1),
                    'date_entree' => now()->subDays(rand(30, 1095)),
                    'salaire_base' => $poste['salaire_base'],
                    'poste' => $poste['nom'],
                    'departement' => $this->getDepartement($poste['nom']),
                    'statut' => 'actif',
                    'entreprise_id' => $entreprise->id,
                    'user_id' => $user->id,
                ]);

                if (rand(1, 3) == 1) {
                    ElementSalaire::create([
                        'type' => 'prime',
                        'nom' => 'Prime de rendement',
                        'montant' => rand(20000, 80000),
                        'employe_id' => $employe->id,
                        'recurrent' => true,
                    ]);
                }

                if (rand(1, 4) == 1) {
                    ElementSalaire::create([
                        'type' => 'indemnite',
                        'nom' => 'Indemnité de transport',
                        'montant' => 25000,
                        'employe_id' => $employe->id,
                        'recurrent' => true,
                    ]);
                }

                if (rand(1, 5) == 1) {
                    ElementSalaire::create([
                        'type' => 'avantage',
                        'nom' => 'Avantage en nature',
                        'montant' => rand(15000, 50000),
                        'employe_id' => $employe->id,
                        'recurrent' => true,
                    ]);
                }

                if (rand(1, 2) == 1) {
                    $salaireCalculator = new SalaireCalculatorService();
                    $salaireCalculator->genererBulletin($employe, 6, 2025);
                }

                if (rand(1, 3) == 1) {
                    $salaireCalculator = new SalaireCalculatorService();
                    $salaireCalculator->genererBulletin($employe, 5, 2025);
                }
            }
        }
    }

    private function getDepartement($poste): string
    {
        $departements = [
            'Gérant' => 'Direction',
            'Sous-gérant' => 'Direction',
            'Pompiste Senior' => 'Carburant',
            'Pompiste' => 'Carburant',
            'Caissier Principal' => 'Caisse',
            'Caissier' => 'Caisse',
            'Boutiquier' => 'Boutique',
            'Responsable Lavage' => 'Lavage',
            'Agent Lavage' => 'Lavage',
            'Mécanicien' => 'Maintenance',
            'Gardien de Nuit' => 'Sécurité',
            'Agent de Sécurité' => 'Sécurité',
            'Nettoyeur' => 'Entretien',
            'Magasinier' => 'Logistique',
            'Comptable' => 'Administration',
            'Secrétaire' => 'Administration',
            'Chauffeur Livreur' => 'Logistique',
            'Agent Commercial' => 'Commercial',
            'Superviseur' => 'Direction',
            'Agent Maintenance' => 'Maintenance'
        ];

        return $departements[$poste] ?? 'Général';
    }
}
