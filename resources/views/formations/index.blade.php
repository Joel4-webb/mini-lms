<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ 
        tab: '{{ auth()->user()->isAdmin() ? 'toutes' : 'apprentissages' }}', 
        search: '', 
        niveau: '' 
    }">
        
        <x-formations.page-header 
            title="Bibliothèque des Formations" 
            subtitle="{{ auth()->user()->isAdmin() ? 'Supervisez l\'ensemble du catalogue et le contenu de la communauté.' : 'Gérez vos apprentissages, explorez le catalogue et créez vos cours.' }}">
            <a href="{{ route('formations.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium text-sm rounded-full hover:bg-indigo-700 transition-colors shadow-sm shrink-0">
                Nouveau Cours
            </a>
        </x-formations.page-header>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- BARRE LATÉRALE : FILTRES -->
            <div class="w-full lg:w-1/4 shrink-0">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 sticky top-6">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-widest mb-6">Filtres</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Mots-clés</label>
                            <input x-model="search" type="text" placeholder="Titre, sujet..." class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 transition-shadow">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Niveau requis</label>
                            <select x-model="niveau" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 transition-shadow cursor-pointer">
                                <option value="">Tous les niveaux</option>
                                <option value="Débutant">Débutant</option>
                                <option value="Intermédiaire">Intermédiaire</option>
                                <option value="Avancé">Avancé</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENU : ONGLETS ET GRILLES DE CARTES -->
            <div class="w-full lg:w-3/4">
                
                <!-- Navigation des onglets -->
                <div class="flex flex-wrap gap-2 mb-8 bg-slate-100/50 p-1.5 rounded-2xl border border-slate-200 max-w-fit">
                    @if(auth()->user()->isAdmin())
                        <button @click="tab = 'toutes'" :class="tab === 'toutes' ? 'bg-white text-indigo-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/75 border-transparent'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all border">Catalogue Global</button>
                        <button @click="tab = 'officielles'" :class="tab === 'officielles' ? 'bg-white text-indigo-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/75 border-transparent'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all border">Mes Créations</button>
                    @else
                        <button @click="tab = 'apprentissages'" :class="tab === 'apprentissages' ? 'bg-white text-indigo-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/75 border-transparent'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all border">Mes Apprentissages</button>
                        <button @click="tab = 'catalogue'" :class="tab === 'catalogue' ? 'bg-white text-indigo-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/75 border-transparent'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all border">Catalogue Ouvert</button>
                        <button @click="tab = 'creations'" :class="tab === 'creations' ? 'bg-white text-indigo-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/75 border-transparent'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all border">Mes Créations</button>
                    @endif
                </div>

                <!-- Grilles d'affichage -->
                <div>
                    @if(auth()->user()->isAdmin())
                        <div x-show="tab === 'toutes'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @forelse($mesCreations as $formation)
                                <div x-show="(search === '' || '{{ strtolower(addslashes($formation->nom)) }}'.includes(search.toLowerCase())) && (niveau === '' || '{{ $formation->niveau }}' === niveau)">
                                    <x-formations.admin-card :formation="$formation" />
                                </div>
                            @empty
                                <x-formations.empty-state title="Catalogue vide" message="Aucune formation sur la plateforme." />
                            @endforelse
                        </div>
                    @else
                        <!-- Onglet : Mes Apprentissages -->
                        <div x-show="tab === 'apprentissages'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @forelse($mesFormations as $formation)
                                <div x-show="(search === '' || '{{ strtolower(addslashes($formation->nom)) }}'.includes(search.toLowerCase())) && (niveau === '' || '{{ $formation->niveau }}' === niveau)">
                                    <x-formations.card :formation="$formation" mode="learning" />
                                </div>
                            @empty
                                <x-formations.empty-state title="Aucun apprentissage" message="Vous n'êtes inscrit à aucune formation." />
                            @endforelse
                        </div>

                        <!-- Onglet : Le Catalogue -->
                        <div x-show="tab === 'catalogue'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @forelse($catalogue as $formation)
                                <div x-show="(search === '' || '{{ strtolower(addslashes($formation->nom)) }}'.includes(search.toLowerCase())) && (niveau === '' || '{{ $formation->niveau }}' === niveau)">
                                    <x-formations.card :formation="$formation" mode="catalog" />
                                </div>
                            @empty
                                <x-formations.empty-state title="Catalogue vide" message="Aucune nouvelle formation disponible." />
                            @endforelse
                        </div>
                    @endif

                    <!-- Onglet "Créations" (Commun Admin & Client) -->
                    <div x-show="tab === 'creations' || tab === 'officielles'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($mesCreations as $formation)
                            @if(!auth()->user()->isAdmin() || (auth()->user()->isAdmin() && $formation->creator_id === auth()->id()))
                                <div x-show="(search === '' || '{{ strtolower(addslashes($formation->nom)) }}'.includes(search.toLowerCase())) && (niveau === '' || '{{ $formation->niveau }}' === niveau)">
                                    <x-formations.card :formation="$formation" mode="creations" />
                                </div>
                            @endif
                        @empty
                            <x-formations.empty-state title="Aucune création" message="Vous n'avez créé aucune formation." />
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>