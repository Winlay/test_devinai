<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bulletin de salaire - {{ $bulletin->employe->nom_complet }} ({{ $bulletin->mois }}/{{ $bulletin->annee }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium mb-4">Informations employé</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom complet</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->employe->nom_complet }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Matricule</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->employe->matricule }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Poste</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->employe->poste ?? 'Non spécifié' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Département</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->employe->departement ?? 'Non spécifié' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium mb-4">Informations entreprise</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->entreprise->nom }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NINEA</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->entreprise->ninea }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Adresse</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $bulletin->entreprise->adresse }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium mb-4">Détail du salaire</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Élément</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Montant (FCFA)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Salaire de base</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">{{ number_format($bulletin->employe->salaire_base, 0, ',', ' ') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Total primes et avantages</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">{{ number_format($bulletin->total_primes, 0, ',', ' ') }}</td>
                                    </tr>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Salaire brut</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Total retenues</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">-{{ number_format($bulletin->total_retenues, 0, ',', ' ') }}</td>
                                    </tr>
                                    <tr class="bg-green-50 dark:bg-green-900">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">Salaire net</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600 dark:text-green-400">{{ number_format($bulletin->salaire_net, 0, ',', ' ') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Généré le {{ $bulletin->date_generation->format('d/m/Y à H:i') }}
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('bulletins.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Retour
                            </a>
                            <a href="{{ route('bulletins.pdf', $bulletin) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Télécharger PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
