<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $entreprise->nom }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('entreprises.edit', $entreprise) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Modifier
                </a>
                <a href="{{ route('entreprises.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informations générales</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">NINEA:</dt>
                                    <dd>{{ $entreprise->ninea }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">Email:</dt>
                                    <dd>{{ $entreprise->email }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">Téléphone:</dt>
                                    <dd>{{ $entreprise->telephone }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">Adresse:</dt>
                                    <dd>{{ $entreprise->adresse }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">Secteur d'activité:</dt>
                                    <dd>{{ $entreprise->secteur_activite ?? 'Non spécifié' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">Date de création:</dt>
                                    <dd>{{ $entreprise->date_creation ? $entreprise->date_creation->format('d/m/Y') : 'Non spécifiée' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">Statut:</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $entreprise->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $entreprise->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Statistiques</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $entreprise->employes->count() }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Employés</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $entreprise->bulletinSalaires->count() }}</div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Bulletins</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($entreprise->employes->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Employés</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom complet</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matricule</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Poste</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salaire de base</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($entreprise->employes as $employe)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $employe->nom_complet }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $employe->matricule }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $employe->poste ?? 'Non spécifié' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ number_format($employe->salaire_base, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($employe->statut === 'actif') bg-green-100 text-green-800 
                                                        @elseif($employe->statut === 'inactif') bg-red-100 text-red-800 
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                                        {{ ucfirst($employe->statut) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="mt-8 text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun employé</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cette entreprise n'a pas encore d'employés enregistrés.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
