<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Saisir une note manuelle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-8 shadow rounded-lg">
            <form action="{{ route('notes.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Choisir l'apprenant :</label>
                    <select name="user_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">-- Sélectionner un élève --</option>
                        @foreach($apprenants as $eleve)
                            <option value="{{ $eleve->id }}">{{ $eleve->name }} ({{ $eleve->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Choisir le Quiz / Leçon :</label>
                    <select name="quiz_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">-- Sélectionner le quiz associé --</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">
                                {{ $quiz->titre }} (Leçon : {{ $quiz->sousChapitre->titre ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Note (sur 20) :</label>
                    <input type="number" step="0.1" name="note" class="w-full border-gray-300 rounded-md shadow-sm" min="0" max="20" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md font-bold hover:bg-blue-600 transition">
                        Enregistrer le résultat 
                    </button>
                </div>
            </form>

            <div class="mb-4">
                <a href="{{ route('notes.index') }}" class="text-sm text-blue-600 hover:underline">← Annuler et retourner à la liste</a>
            </div>
        </div>
    </div>
</x-app-layout>