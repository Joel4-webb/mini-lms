<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <h1 class="text-2xl font-bold mb-6">Modifier / Rattacher le Quiz</h1>

        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg border">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block font-bold mb-2">Titre du Quiz</label>
                <input type="text" name="titre" value="{{ $quiz->titre }}" class="w-full border-gray-300 rounded-lg">
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2">Assigner au Chapitre :</label>
                <select name="chapitre_id" class="w-full border-gray-300 rounded-lg">
                    <option value="">-- Choisir le chapitre correspondant --</option>
                    @foreach($chapitres as $chapitre)
                        <option value="{{ $chapitre->id }}" {{ $quiz->chapitre_id == $chapitre->id ? 'selected' : '' }}>
                            {{ $chapitre->titre }} (Formation: {{ $chapitre->formation->titre ?? '?' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-2">C'est ce choix qui fera apparaître le bouton sur les leçons !</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-2 rounded-lg font-bold">
                    Enregistrer et Relier
                </button>
            </div>
        </form>
    </div>
</x-app-layout>