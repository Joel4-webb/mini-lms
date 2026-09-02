<x-guest-layout>
    <div class="w-full max-w-md mx-auto p-8 sm:p-10 bg-white rounded-[2rem] shadow-sm border border-slate-100 relative z-10">
        
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Bienvenue</h2>
            <p class="text-sm text-slate-500 font-medium mt-2">Créez votre compte pour commencer à forger vos cours.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Nom complet -->
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                       placeholder="Ex: Jean Dupont">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                       placeholder="jean.dupont@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Bouton -->
            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-2xl shadow-sm shadow-indigo-200 transition-all">
                    Créer mon compte
                </button>
            </div>
        </form>

        <p class="text-center mt-8 text-sm font-medium text-slate-500">
            Déjà inscrit ? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Connexion</a>
        </p>
    </div>
</x-guest-layout>