<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <x-formations.page-header 
                title="Nouvelle formation" 
                subtitle="Initialisez un nouveau parcours d'apprentissage guidé par l'IA." />

            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- COLONNE GAUCHE : Le Formulaire -->
                <div class="w-full lg:w-2/3 bg-white rounded-3xl shadow-sm border border-slate-100 p-8 sm:p-10">
                    <form action="{{ route('formations.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Champ Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-bold text-slate-700 mb-2">Titre de la formation *</label>
                            <input type="text" name="nom" id="nom" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                                   placeholder="Ex: Maîtriser Laravel en 30 jours">
                        </div>

                        <!-- Champ Niveau -->
                        <div>
                            <label for="niveau" class="block text-sm font-bold text-slate-700 mb-2">Niveau</label>
                            <select name="niveau" id="niveau" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800 cursor-pointer">
                                <option value="" disabled selected>Sélectionner un niveau d'exigence</option>
                                <option value="Débutant">Débutant</option>
                                <option value="Intermédiaire">Intermédiaire</option>
                                <option value="Avancé">Avancé</option>
                            </select>
                        </div>

                        <!-- Champ Description -->
                        <div>
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description globale</label>
                            <textarea name="description" id="description" rows="5" 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow text-slate-800 resize-none"
                                      placeholder="Décrivez le but principal de ce parcours d'apprentissage..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="pt-6 border-t border-slate-100 flex justify-end gap-4 mt-8">
                            <a href="{{ route('formations.index') }}" class="px-6 py-3 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors flex items-center">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-full shadow-sm shadow-indigo-200 transition-all flex items-center gap-2 text-sm">
                                Initier la formation
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- COLONNE DROITE : Panneau d'informations -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-indigo-50 rounded-3xl p-8 border border-indigo-100 sticky top-6">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold mb-4 shadow-sm shadow-indigo-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-3">Préparer le terrain pour l'IA</h3>
                        <p class="text-indigo-900/70 text-sm leading-relaxed mb-6 font-medium">
                            Soyez le plus précis possible dans la rédaction de votre description. Notre modèle d'IA s'appuiera sur ces informations globales pour garantir la cohérence des futurs chapitres.
                        </p>
                        <div class="border-t border-indigo-200/60 pt-4">
                            <p class="text-xs text-indigo-500 uppercase font-bold tracking-wider">Étape 1 sur 2</p>
                            <p class="text-sm font-bold text-indigo-900 mt-1">Génération du contenu à l'étape suivante.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>