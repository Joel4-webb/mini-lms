<x-app-layout>
    <!-- Importation du parseur Markdown pour l'aperçu -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- En-tête -->
    <div class="bg-white border-b border-slate-200 px-4 py-6 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <nav class="flex items-center text-sm text-slate-500 mb-2 font-medium">
                    <a href="{{ route('formations.show', $chapitre->formation_id) }}" class="hover:text-indigo-600 transition">{{ $chapitre->formation->nom }}</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-slate-800">{{ $chapitre->titre }}</span>
                </nav>
                <h2 class="font-black text-3xl text-slate-800 leading-tight tracking-tight">Nouvelle Leçon</h2>
            </div>
        </div>
    </div>

    <!-- Conteneur Alpine connecté à notre script -->
    <div class="py-10 bg-slate-50 min-h-screen" x-data="editeurLecon">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('sous-chapitres.store') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                @csrf
                <input type="hidden" name="chapitre_id" value="{{ $chapitre->id }}">

                <!-- Barre d'outils et Titre -->
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex flex-col md:flex-row gap-6 justify-between items-start md:items-end">
                        <div class="w-full md:w-2/3">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Titre de la leçon *</label>
                            <input type="text" name="titre" x-model="titre" placeholder="Ex: Les verbes fréquents en anglais" required
                                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 shadow-sm transition-shadow">
                        </div>
                        
                        <button type="button" @click="genererIA()" :disabled="isGenerating" 
                                class="shrink-0 flex items-center gap-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white px-5 py-3 rounded-xl font-bold text-sm transition-colors border border-indigo-100 hover:border-indigo-600 disabled:opacity-50">
                            <svg x-show="!isGenerating" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <svg x-show="isGenerating" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="isGenerating ? 'Création en cours...' : 'Générer via IA (-10 Pts)'"></span>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Système d'onglets -->
                    <div class="flex gap-4 mb-4 border-b border-slate-100 pb-4">
                        <button type="button" @click="tab = 'edit'" :class="tab === 'edit' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-colors">
                            Éditeur Markdown
                        </button>
                        <button type="button" @click="tab = 'preview'" :class="tab === 'preview' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-colors">
                            Aperçu Rendu
                        </button>
                    </div>

                    <!-- Zone de contenu -->
                    <div class="relative min-h-[400px]">
                        <!-- Onglet : Édition brute -->
                        <div x-show="tab === 'edit'">
                            <textarea name="contenu" x-model="contenu" rows="15" required placeholder="Saisissez le contenu du cours ou utilisez l'IA..."
                                      class="w-full px-6 py-5 bg-slate-50 border border-slate-200 rounded-2xl font-mono text-sm text-slate-700 leading-relaxed focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow resize-y"></textarea>
                        </div>

                        <!-- Onglet : Aperçu visuel rendu -->
                        <div x-show="tab === 'preview'" style="display: none;" 
                             class="w-full px-8 py-8 bg-white border border-slate-200 rounded-2xl min-h-[400px] shadow-inner overflow-y-auto">
                            
                            <!-- Le texte brut est interprété avec des styles forcés pour remplacer le plugin Prose -->
                            <div x-html="contenu ? marked.parse(contenu) : '<p class=\'text-slate-400 italic\'>Aucun contenu à afficher.</p>'" 
                                 class="max-w-none text-slate-700 leading-relaxed
                                        [&>h1]:text-3xl [&>h1]:font-black [&>h1]:text-slate-900 [&>h1]:mt-8 [&>h1]:mb-6
                                        [&>h2]:text-2xl [&>h2]:font-bold [&>h2]:text-slate-800 [&>h2]:mt-10 [&>h2]:mb-5 [&>h2]:border-b [&>h2]:border-slate-100 [&>h2]:pb-2
                                        [&>h3]:text-xl [&>h3]:font-bold [&>h3]:text-slate-800 [&>h3]:mt-8 [&>h3]:mb-4
                                        [&>p]:mb-6 [&>p]:text-lg
                                        [&>ul]:list-disc [&>ul]:pl-6 [&>ul]:mb-6 [&>ul>li]:mb-2 [&>ul>li]:text-lg
                                        [&>ol]:list-decimal [&>ol]:pl-6 [&>ol]:mb-6 [&>ol>li]:mb-2 [&>ol>li]:text-lg
                                        [&>strong]:font-bold [&>strong]:text-slate-900"
                                 :class="{'animate-pulse opacity-50': isGenerating}">
                            </div>
                        </div>
                    </div>

                    <!-- Options supplémentaires et Validation -->
                    <div class="mt-8 pt-6 border-t border-slate-100 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Résumé rapide</label>
                            <textarea name="resume" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 text-slate-700"></textarea>
                        </div>

                        <button type="submit" :disabled="isGenerating" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-lg py-4 rounded-xl shadow-sm transition-colors disabled:opacity-50">
                            Publier la leçon
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script détaché pour éviter les conflits de guillemets -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editeurLecon', () => ({
                tab: 'edit', 
                contenu: '', 
                titre: '',
                isGenerating: false,
                
                genererIA() {
                    if (!this.titre) {
                        alert('Veuillez d\'abord définir un titre de leçon.');
                        return;
                    }
                    this.isGenerating = true;
                    this.contenu = 'Génération du contenu pédagogique en cours... Veuillez patienter.';
                    this.tab = 'preview';

                    const promptTexte = `Agis comme un professeur expert. Rédige un cours complet pour la leçon : '${this.titre}'. 
                    Contexte : Chapitre '{{ addslashes($chapitre->titre) }}' de la formation '{{ addslashes($chapitre->formation->nom) }}'.
                    Structure requise en Markdown (## Introduction, ## I. Partie, ## Conclusion). Pas de tableaux.`;

                    fetch('{{ route("ai.generate") }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ prompt: promptTexte })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isGenerating = false;
                        if(data.success) {
                            this.contenu = data.reply;
                        } else {
                            alert('Erreur: ' + data.message);
                            this.contenu = '';
                        }
                    }).catch(() => {
                        this.isGenerating = false;
                        alert('Erreur de connexion avec le serveur IA.');
                        this.contenu = '';
                    });
                }
            }));
        });
    </script>
</x-app-layout>