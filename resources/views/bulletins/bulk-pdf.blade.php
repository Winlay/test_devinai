<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bulletins de Salaire - {{ $entreprise->nom }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .page-break { page-break-before: always; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { margin-bottom: 20px; }
        .employee-info { margin-bottom: 20px; }
        .salary-details { width: 100%; border-collapse: collapse; }
        .salary-details th, .salary-details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .salary-details th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    @foreach($bulletins as $index => $bulletin)
        @if($index > 0)
            <div class="page-break"></div>
        @endif
        
        <div class="header">
            <h1>BULLETIN DE SALAIRE</h1>
            <h2>{{ $bulletin->mois }}/{{ $bulletin->annee }}</h2>
        </div>

        <div class="company-info">
            <h3>ENTREPRISE</h3>
            <p><strong>{{ $bulletin->entreprise->nom }}</strong></p>
            <p>NINEA: {{ $bulletin->entreprise->ninea }}</p>
            <p>{{ $bulletin->entreprise->adresse }}</p>
            <p>Tél: {{ $bulletin->entreprise->telephone }}</p>
            <p>Email: {{ $bulletin->entreprise->email }}</p>
        </div>

        <div class="employee-info">
            <h3>EMPLOYÉ</h3>
            <p><strong>{{ $bulletin->employe->nom_complet }}</strong></p>
            <p>Matricule: {{ $bulletin->employe->matricule }}</p>
            <p>Poste: {{ $bulletin->employe->poste ?? 'Non spécifié' }}</p>
            <p>Département: {{ $bulletin->employe->departement ?? 'Non spécifié' }}</p>
        </div>

        <table class="salary-details">
            <thead>
                <tr>
                    <th>ÉLÉMENTS</th>
                    <th>MONTANT (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Salaire de base</td>
                    <td>{{ number_format($bulletin->employe->salaire_base, 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>Total primes et avantages</td>
                    <td>{{ number_format($bulletin->total_primes, 0, ',', ' ') }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>SALAIRE BRUT</strong></td>
                    <td><strong>{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</strong></td>
                </tr>
                <tr>
                    <td>Total retenues</td>
                    <td>{{ number_format($bulletin->total_retenues, 0, ',', ' ') }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>SALAIRE NET</strong></td>
                    <td><strong>{{ number_format($bulletin->salaire_net, 0, ',', ' ') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Bulletin généré le {{ $bulletin->date_generation->format('d/m/Y') }}</p>
            <p>Ce document est confidentiel et ne peut être reproduit sans autorisation.</p>
        </div>
    @endforeach
</body>
</html>
