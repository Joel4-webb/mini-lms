<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Tableau de bord</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-gray-600">Édition</span>
                </nav>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    Modifier la formation : <span class="text-indigo-600">{{ $formation->nom }}</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- FORMULAIRE PRINCIPAL DES INFOS --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest text-indigo-500">Informations générales</h3>
                </div>

                <form method="POST" action="{{ route('formations.update', $formation->id) }}" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-black text-gray-700 mb-2">Nom de la formation</label>
                            <input type="text" name="nom" id="nom" value="{{ $formation->nom }}" 
                                   class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800" required>
                        </div>

                        <div>
                            <label for="niveau" class="block text-sm font-black text-gray-700 mb-2">Niveau</label>
                            <select name="niveau" id="niveau" class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800">
                                <option value="Débutant" {{ $formation->niveau == 'Débutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="Intermédiaire" {{ $formation->niveau == 'Intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="Avancé" {{ $formation->niveau == 'Avancé' ? 'selected' : '' }}>Avancé</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-black text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800">{{ $formation->description }}</textarea>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                            💾 Mettre à jour les informations
                        </button>
                    </div>
                </form>
            </div>

            {{-- SECTION : ASSOCIATION DES APPRENANTS --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                    <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest text-indigo-500">Gestion des inscriptions</h3>
                    <span class="bg-indigo-100 text-indigo-600 text-[10px] px-3 py-1 rounded-full font-black uppercase">
                        {{ $formation->apprenants->count() }} Inscrits
                    </span>
                </div>

                {{-- Note: Utilisation d'une route spécifique pour le lien pivot --}}
                <form action="{{ route('formations.assign', $formation->id) }}" method="POST" class="p-8">
                    @csrf
                    <p class="text-sm text-gray-500 mb-6 font-medium">Sélectionnez les apprenants autorisés à consulter ce contenu pédagogique.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($tousLesApprenants as $apprenant)
                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-indigo-50 transition border border-transparent has-[:checked]:border-indigo-200 has-[:checked]:bg-white group">
                                <input type="checkbox" name="apprenants[]" value="{{ $apprenant->id }}" 
                                       {{ $formation->apprenants->contains($apprenant->id) ? 'checked' : '' }}
                                       class="w-6 h-6 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 transition">
                                <div class="ml-4">
                                    <p class="font-bold text-gray-800 group-hover:text-indigo-600 transition">{{ $apprenant->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tight">{{ $apprenant->email }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <button type="submit" class="w-full md:w-auto bg-gray-900 text-white px-10 py-4 rounded-2xl font-black shadow-xl hover:bg-indigo-600 transition-all">
                            ✅ Valider les inscriptions
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>