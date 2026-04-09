<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                {{-- Breadcrumbs --}}
                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition">Formations</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <a href="{{ route('chapitres.show', $quiz->sousChapitre->chapitre_id) }}" class="hover:text-indigo-600 transition">Gestion Chapitre</a>
                </nav>
                <h2 class="font-black text-3xl text-gray-800 leading-tight">
                    ⚙️ Gérer le Quiz : <span class="text-indigo-600">{{ $quiz->titre }}</span>
                </h2>
            </div>
            
            <a href="{{ route('chapitres.show', $quiz->sousChapitre->chapitre_id) }}" 
               class="flex items-center text-sm font-bold text-gray-500 hover:text-indigo-600 transition group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"></path></svg>
                Retour au chapitre
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SECTION 1 : QUESTIONS EXISTANTES --}}
            <div class="mb-12">
                <h3 class="text-xl font-black text-gray-800 flex items-center mb-6">
                    📚 Questions enregistrées 
                    <span class="ml-3 px-3 py-1 rounded-full text-xs font-black bg-indigo-100 text-indigo-600">
                        {{ $quiz->questions->count() }}
                    </span>
                </h3>
                
                <div class="space-y-4">
                    @forelse($quiz->questions as $question)
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-black text-gray-900 mb-3 text-lg">{{ $question->texte_question }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($question->reponses as $reponse)
                                        <div class="flex items-center p-2 rounded-xl {{ $reponse->est_correcte ? 'bg-green-50' : 'bg-gray-50/50' }}">
                                            <span class="w-1.5 h-1.5 rounded-full mx-2 {{ $reponse->est_correcte ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                            <span class="text-sm {{ $reponse->est_correcte ? 'text-green-700 font-bold' : 'text-gray-500' }}">{{ $reponse->texte_reponse }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <form action="{{ route('questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-3 text-gray-300 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-3xl bg-white/50 text-gray-400 font-bold italic">
                            Aucune question créée.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- SECTION 2 : ASSISTANT IA --}}
            <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-8 shadow-xl mb-12 text-white relative overflow-hidden">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-white/20 rounded-2xl mr-5">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-2xl">Assistant Magique IA</h3>
                        <p class="text-indigo-100 text-sm">Analyse de : {{ $quiz->sousChapitre->titre }}</p>
                    </div>
                </div>
                <input type="hidden" id="contenu_source" value="{{ $quiz->sousChapitre->contenu ?? '' }}">
                <button type="button" id="btn-generate" onclick="window.genererQuestionsIA()" 
                        class="w-full bg-white text-indigo-600 py-4 rounded-2xl font-black text-lg shadow-xl hover:bg-indigo-50 transition-all active:scale-95">
                    ✨ Générer des questions intelligentes
                </button>
            </div>

            {{-- SECTION 3 : FORMULAIRE D'ENREGISTREMENT --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Édition des nouvelles questions</h3>
                </div>

                <form method="POST" action="{{ route('quizzes.questions.store', $quiz->id) }}" class="p-8">
                    @csrf
                    <div id="questions-container" class="space-y-8">
                        <div id="placeholder-text" class="text-center py-16">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <p class="text-gray-400 font-bold italic">Utilisez l'assistant IA ou ajoutez une question manuellement.</p>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-6">
                        <button type="button" id="btn-manuel" class="flex items-center text-indigo-600 font-black hover:text-indigo-800 transition group text-sm">
                            <div class="p-2 bg-indigo-50 rounded-lg mr-2 group-hover:bg-indigo-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            Ajouter manuellement
                        </button>
                        
                        <button type="submit" class="w-full sm:w-auto bg-green-500 text-white px-12 py-4 rounded-2xl font-black text-lg shadow-2xl hover:bg-green-600 transition-all">
                            💾 Enregistrer le contenu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation du compteur (basé sur l'existant)
            let qCount = parseInt("{{ $quiz->questions->count() }}") || 0;
            const container = document.getElementById('questions-container');
            const placeholder = document.getElementById('placeholder-text');
            const btnManual = document.getElementById('btn-manuel');

            // 1. Fonction Template (Unifiée)
            function generateHTML(index, questionText = '', options = []) {
                const finalOptions = [0, 1, 2, 3].map(i => options[i] || { texte: '', correct: false });
                
                return `
                    <div class="question-card p-8 border border-indigo-100 rounded-[2rem] bg-gray-50/50 mb-8 shadow-inner animate-fade-in-down">
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Question n°${index + 1}</label>
                                <button type="button" onclick="this.closest('.question-card').remove()" class="text-rose-500 text-[10px] font-bold uppercase">Supprimer</button>
                            </div>
                            <input type="text" name="questions[${index}][texte]" value="${questionText}" placeholder="Saisissez votre question..." required
                                   class="w-full border-transparent bg-white shadow-sm rounded-2xl py-4 px-6 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${finalOptions.map((opt, i) => `
                                <div class="flex items-center space-x-3 p-4 bg-white border border-gray-100 rounded-2xl shadow-sm">
                                    <input type="checkbox" name="questions[${index}][reponses][${i}][correct]" value="1" ${opt.correct ? 'checked' : ''} class="w-6 h-6 text-indigo-600 rounded-lg">
                                    <input type="text" name="questions[${index}][reponses][${i}][texte]" value="${opt.texte}" placeholder="Option ${i+1}" required
                                           class="w-full border-none p-0 focus:ring-0 text-sm font-bold text-gray-700">
                                </div>
                            `).join('')}
                        </div>
                    </div>`;
            }

            // 2. Gestionnaire d'ajout manuel
            if (btnManual) {
                btnManual.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log("Clic détecté via btnManual !");
                    
                    if (placeholder) placeholder.style.display = 'none';
                    
                    const html = generateHTML(qCount);
                    container.insertAdjacentHTML('beforeend', html);
                    qCount++;
                });
            }

            // 3. Gestionnaire IA (Global)
            window.genererQuestionsIA = async function() {
                const btnIA = document.getElementById('btn-generate');
                const content = document.getElementById('contenu_source')?.value;

                if (!content) return alert("Le contenu est vide.");

                btnIA.disabled = true;
                const originalText = btnIA.innerText;
                btnIA.innerText = "✨ Analyse en cours...";

                try {
                    const response = await fetch("{{ route('ai.generate-quiz') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ contenu: content })
                    });
                    const data = await response.json();
                    
                    if (data.success && data.questions) {
                        if (placeholder) placeholder.style.display = 'none';
                        data.questions.forEach(q => {
                            // On utilise le même template pour l'IA et le manuel
                            container.insertAdjacentHTML('beforeend', generateHTML(qCount, q.question, q.options));
                            qCount++;
                        });
                    } else {
                        alert("L'IA n'a pas pu générer de questions.");
                    }
                } catch (e) {
                    alert("Erreur de communication avec l'IA.");
                } finally {
                    btnIA.disabled = false;
                    btnIA.innerText = originalText;
                }
            };
        });
    </script>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</x-app-layout>