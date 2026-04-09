<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier le chapitre : {{ $chapitre->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('chapitres.update', $chapitre->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="formation_id" class="block text-gray-700 font-bold mb-2">Appartient à la formation :</label>
                        <select name="formation_id" id="formation_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($formations as $formation)
                                <option value="{{ $formation->id }}" {{ $chapitre->formation_id == $formation->id ? 'selected' : '' }}>
                                    {{ $formation->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="titre" class="block text-gray-700 font-bold mb-2">Titre du chapitre</label>
                        <input type="text" name="titre" id="titre" value="{{ $chapitre->titre }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ $chapitre->description }}</textarea>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour le chapitre</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>