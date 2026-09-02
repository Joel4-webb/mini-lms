<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="scrolled ? 'bg-indigo-50/95 border-indigo-200 shadow-sm' : 'bg-white/80 border-slate-200'"
     class="backdrop-blur-md border-b sticky top-0 z-50 transition-all duration-300">
     
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            {{-- 1. LOGO (Gauche) --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center shrink-0">
                    <span class="font-black text-2xl text-slate-900 tracking-tighter transition-colors">
                        MINI<span class="text-indigo-600">LMS</span>
                    </span>
                </a>
            </div>

            {{-- 2. LIENS CENTRAUX (Milieu) --}}
            <div class="hidden md:flex items-center gap-2 text-sm font-bold">
                
                @if(Auth::user()->role === 'admin')
                    <!-- Navigation Administrateur -->
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-100/50' }}">
                        Tableau de bord
                    </a>

                    <a href="{{ route('formations.index') }}" 
                       class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('formations.*') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-100/50' }}">
                        Gestion Formations
                    </a>

                    <a href="{{ route('notes.index') }}" 
                       class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('notes.*') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-100/50' }}">
                        Suivi Évaluations
                    </a>
                @else
                    <!-- Navigation Apprenant -->
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-100/50' }}">
                        Mes leçons
                    </a>

                    <a href="{{ route('formations.index') }}" 
                       class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('formations.*') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-100/50' }}">
                        Catalogue
                    </a>

                    <a href="{{ route('notes.index') }}" 
                       class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('notes.*') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-100/50' }}">
                        Résultats
                    </a>
                @endif
            </div>

            {{-- 3. GAMIFICATION & PROFIL (Droite) --}}
            <div class="flex items-center gap-4 sm:gap-6">
                
               <!-- Solde de points IA -->
                <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-100 text-amber-600 rounded-xl text-sm font-black shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"/></svg>
                    {{ Auth::user()->role === 'admin' ? 'Infini' : (Auth::user()->points_balance ?? 0) . ' Pts' }}
                </div>

                <!-- Dropdown Profil -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center focus:outline-none">
                            <div class="w-10 h-10 rounded-xl border-2 border-slate-100 hover:border-indigo-300 overflow-hidden transition-all shadow-sm">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=EEF2FF&color=4F46E5&bold=true" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 font-medium truncate">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm font-semibold text-slate-700 hover:text-indigo-600">
                            Mon Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- Bouton Menu Mobile -->
                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Mobile Dropdown -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full transition-all">
        <div class="px-4 py-3 border-b border-slate-50 bg-amber-50 flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"/></svg>
            <span class="text-sm font-black text-amber-700">
                {{ Auth::user()->role === 'admin' ? 'Solde Infini' : (Auth::user()->points_balance ?? 0) . ' Pts disponibles' }}
            </span>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Tableau de bord</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('formations.index')" :active="request()->routeIs('formations.*')">Gestion Formations</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')">Suivi Évaluations</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Mes leçons</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('formations.index')" :active="request()->routeIs('formations.*')">Catalogue</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')">Résultats</x-responsive-nav-link>
            @endif
        </div>
    </div>
</nav>