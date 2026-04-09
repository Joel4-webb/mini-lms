<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('formations.index') }}" class="p-2 bg-white rounded-xl shadow-sm text-gray-400 hover:text-indigo-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-black text-2xl text-gray-800 leading-tight">
                🆕 Créer une nouvelle formation
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/50 border border-gray-100 overflow-hidden">
                
                {{-- En-tête du formulaire --}}
                <div class="p-8 bg-indigo-600 text-white">
                    <h3 class="text-xl font-bold">Informations Générales</h3>
                    <p class="text-indigo-100 text-sm opacity-80">Définissez le titre et l'objectif de votre formation.</p>
                </div>

                <form action="{{ route('formations.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    {{-- Champ Nom --}}
                    <div>
                        <label for="nom" class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-2">Titre de la formation</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <input type="text" name="nom" id="nom" required 
                                   class="block w-full pl-12 pr-4 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-bold text-gray-800"
                                   placeholder="Ex: Maîtriser Laravel en 30 jours">
                        </div>
                    </div>


                    <div class="mb-4">
                        <label for="niveau" class="block text-sm font-black text-gray-700 mb-2">Niveau de la formation</label>
                        <select name="niveau" id="niveau" class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800" required>
                            <option value="" disabled selected>Choisir un niveau</option>
                            <option value="Débutant">Débutant</option>
                            <option value="Intermédiaire">Intermédiaire</option>
                            <option value="Avancé">Avancé</option>
                        </select>
                    </div>

                    {{-- Champ Description --}}
                    <div>
                        <label for="description" class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="description" id="description" rows="5" 
                                  class="block w-full p-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all text-gray-700"
                                  placeholder="Décrivez brièvement ce que les élèves vont apprendre..."></textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-50">
                        <a href="{{ route('formations.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95">
                            🚀 Créer la formation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>