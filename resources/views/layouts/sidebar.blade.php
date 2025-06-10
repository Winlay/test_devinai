<div class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex flex-col shadow-xl">
    <!-- Logo Section -->
    <div class="p-6 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold">PayRoll Pro</h1>
                <p class="text-blue-300 text-sm">Gestion de Paie</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            <span>Tableau de bord</span>
        </a>

        @if(auth()->user()->isAdmin())
        <!-- Entreprises (Admin only) -->
        <a href="{{ route('entreprises.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('entreprises.*') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span>Entreprises</span>
        </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
        <!-- Employés -->
        <a href="{{ route('employes.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('employes.*') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            <span>Employés</span>
        </a>

        <!-- Éléments de Salaire -->
        <a href="{{ route('element-salaires.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('element-salaires.*') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            <span>Éléments de Salaire</span>
        </a>

        <!-- Absences -->
        <a href="{{ route('absences.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('absences.*') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Absences</span>
        </a>

        <!-- Retenues -->
        <a href="{{ route('retenues.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('retenues.*') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"></path>
            </svg>
            <span>Retenues</span>
        </a>
        @endif

        <!-- Bulletins de Salaire -->
        <a href="{{ route('bulletins.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('bulletins.*') ? 'bg-green-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Bulletins de Salaire</span>
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isResponsableEntreprise())
        <!-- Divider -->
        <div class="border-t border-blue-700 my-4"></div>

        <!-- Actions rapides -->
        <div class="px-4 py-2">
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider">Actions rapides</p>
        </div>

        <a href="{{ route('bulletins.create') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-blue-100 hover:bg-green-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Générer Bulletin</span>
        </a>

        <a href="{{ route('employes.create') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-blue-100 hover:bg-green-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <span>Nouvel Employé</span>
        </a>
        @endif
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-300 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</div>
