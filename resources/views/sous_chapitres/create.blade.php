<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                {{-- Breadcrumbs pour la navigation logique  --}}
                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition">Catalogue</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-gray-600">Nouveau contenu</span>
                </nav>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    📖 Créer une <span class="text-indigo-600">Leçon</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                    <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest text-indigo-500">Contenu Pédagogique </h3>
                    
                    {{-- Bouton IA pour l'import assisté 
                     --}}
                    <button type="button" onclick="genererCoursIA()" class="flex items-center text-xs font-black bg-amber-100 text-amber-700 px-4 py-2 rounded-xl hover:bg-amber-200 transition shadow-sm border border-amber-200">
                        <span class="mr-2">✨</span> Générer par IA
                    </button>
                </div>

                <form action="{{ route('sous-chapitres.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="chapitre_id" value="{{ $chapitre_id }}">

                    {{-- 1. Titre du contenu  --}}
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2">Titre du sous-chapitre</label>
                        <input type="text" name="titre" id="titre_lecon" placeholder="Ex: Les verbes fréquents en anglais " required
                               class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800 transition">
                    </div>

                    {{-- 2. Résumé du contenu  --}}
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2">Résumé / Introduction</label>
                        <textarea name="resume" id="resume_cours" rows="2" placeholder="Un court texte pour introduire la leçon... 
                        "
                                  class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 font-bold text-gray-800 transition"></textarea>
                    </div>

                    {{-- 3. Corps du cours (Texte pédagogique)  --}}
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2">Texte du cours </label>
                        <textarea name="contenu" id="contenu_cours" rows="10" placeholder="Contenu détaillé de la leçon... " required
                                  class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 font-medium text-gray-700 leading-relaxed transition"></textarea>
                    </div>

                    {{-- 4. Lien de ressource  --}}
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2">Lien de ressource externe</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            <input type="url" name="lien_ressource" placeholder="https://ressources.com/verbes-anglais.pdf"
                                   class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 pl-14 pr-6 focus:ring-4 focus:ring-indigo-100 font-bold text-indigo-600 transition">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                            ✅ Enregistrer et Publier la Leçon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function genererCoursIA() {
            const titreInput = document.getElementById('titre_lecon');
            const textarea = document.getElementById('contenu_cours');
            const resumeArea = document.getElementById('resume_cours');
            
            if (!titreInput.value) {
                alert("Veuillez saisir un titre pour guider l'IA.");
                return;
            }

            textarea.value = "Génération du contenu pédagogique en cours... ";
            textarea.classList.add('animate-pulse');

            fetch("/generate-ai", { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    prompt: "Rédige un cours pédagogique complet et un court résumé pour : " + titreInput.value 
                })
            })
            .then(response => response.json())
            .then(data => {
                textarea.classList.remove('animate-pulse');
                if (data.success) {
                    // Si votre API renvoie un objet avec contenu et résumé, adaptez ici
                    textarea.value = data.reply;
                } else {
                    alert("Erreur IA : " + data.message);
                }
            })
            .catch(error => {
                textarea.classList.remove('animate-pulse');
                console.error('Erreur:', error);
                alert("Erreur de connexion avec le service IA.");
            });
        }
    </script>
</x-app-layout>