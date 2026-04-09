<x-app-layout>
    <div class="max-w-2xl mx-auto py-12">
        <h1 class="text-2xl font-bold mb-6">Nouveau Chapitre</h1>
        <form action="{{ route('chapitres.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border">
            @csrf
            <input type="hidden" name="formation_id" value="{{ $formation_id }}">
            
            <div class="mb-4">
                <label class="block font-bold mb-2">Titre du chapitre</label>
                <input type="text" name="titre" class="w-full border-gray-300 rounded-lg" placeholder="Ex: Fondamentaux du PHP" required>
            </div>
            
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold">Enregistrer le Chapitre</button>
        </form>
    </div>
</x-app-layout>