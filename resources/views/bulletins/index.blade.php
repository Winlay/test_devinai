<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Bulletins de salaire
            </h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
                <a href="{{ route('bulletins.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Générer bulletin
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('bulletins.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @if(auth()->user()->isAdmin())
                            <div>
                                <label for="entreprise_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entreprise</label>
                                <select name="entreprise_id" id="entreprise_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Toutes les entreprises</option>
                                    @foreach(\App\Models\Entreprise::all() as $entreprise)
                                        <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                            {{ $entreprise->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                        <div>
                            <label for="mois" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mois</label>
                            <select name="mois" id="mois" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Tous les mois</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('mois') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label for="annee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Année</label>
                            <select name="annee" id="annee" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Toutes les années</option>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Génération PDF groupée</h3>
                    <form method="GET" action="{{ route('bulletins.bulk-pdf') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @if(auth()->user()->isAdmin())
                            <div>
                                <label for="bulk_entreprise_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entreprise</label>
                                <select name="entreprise_id" id="bulk_entreprise_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Sélectionner une entreprise</option>
                                    @foreach(\App\Models\Entreprise::all() as $entreprise)
                                        <option value="{{ $entreprise->id }}">{{ $entreprise->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="entreprise_id" value="{{ auth()->user()->entreprise_id }}">
                        @endif
                        
                        <div>
                            <label for="bulk_mois" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mois</label>
                            <select name="mois" id="bulk_mois" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Sélectionner un mois</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label for="bulk_annee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Année</label>
                            <select name="annee" id="bulk_annee" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Sélectionner une année</option>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Télécharger PDF groupé
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employé</th>
                                    @endif
                                    @if(auth()->user()->isAdmin())
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Entreprise</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Période</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salaire brut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salaire net</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($bulletins as $bulletin)
                                    <tr>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $bulletin->employe->nom_complet }}
                                            </td>
                                        @endif
                                        @if(auth()->user()->isAdmin())
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $bulletin->entreprise->nom }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $bulletin->mois }}/{{ $bulletin->annee }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ number_format($bulletin->salaire_brut, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($bulletin->salaire_net, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($bulletin->statut === 'valide') bg-green-100 text-green-800 
                                                @elseif($bulletin->statut === 'paye') bg-blue-100 text-blue-800 
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($bulletin->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('bulletins.show', $bulletin) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</a>
                                            <a href="{{ route('bulletins.pdf', $bulletin) }}" class="text-green-600 hover:text-green-900 mr-3">PDF</a>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
                                                <form action="{{ route('bulletins.destroy', $bulletin) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $bulletins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
