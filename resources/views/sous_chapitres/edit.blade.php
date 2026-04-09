<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4">
        <h1 class="text-2xl font-bold mb-6">Modifier la leçon : {{ $sousChapitre->titre }}</h1>

        <form action="{{ route('sous-chapitres.update', $sousChapitre->id) }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block font-bold mb-2">Titre de la leçon</label>
                <input type="text" name="titre" value="{{ $sousChapitre->titre }}" class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2">Contenu (Texte IA ou Manuel)</label>
                <textarea name="contenu" rows="15" class="w-full border-gray-300 rounded-lg shadow-sm font-serif text-lg">{{ $sousChapitre->contenu }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('chapitres.show', $sousChapitre->chapitre_id) }}" class="px-6 py-2 text-gray-600">Annuler</a>
                <button type="submit" class="bg-green-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-green-700">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</x-app-layout>