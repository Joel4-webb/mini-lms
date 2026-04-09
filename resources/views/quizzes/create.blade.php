<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer un nouveau Quiz
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('quizzes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Titre du Quiz</label>
                        <input type="text" name="titre" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: Évaluation : Les bases du PHP" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Associer à la leçon (Sous-chapitre)</label>
                        <select name="sous_chapitre_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Sélectionnez une leçon --</option>
                            @foreach(App\Models\SousChapitre::with('chapitre.formation')->get() as $lecon)
                                <option value="{{ $lecon->id }}" {{ request('sous_chapitre_id') == $lecon->id ? 'selected' : '' }}>
                                    {{ $lecon->titre }} (Chapitre: {{ $lecon->chapitre->titre }} | Formation: {{ $lecon->chapitre->formation->titre }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 italic">Conformément au sujet : 1 quiz par sous-chapitre.</p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded font-bold hover:bg-blue-600 transition shadow">
                            Étape suivante : Ajouter des questions &rarr;
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>