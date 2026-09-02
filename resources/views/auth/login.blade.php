<x-guest-layout>
    <div class="w-full max-w-md mx-auto p-8 sm:p-10 bg-white rounded-[2rem] shadow-sm border border-slate-100 relative z-10">
        
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Bon retour</h2>
            <p class="text-sm text-slate-500 font-medium mt-2">Connectez-vous pour continuer votre apprentissage.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                       placeholder="jean.dupont@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                            Oublié ?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow font-medium text-slate-800"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer">
                <label for="remember_me" class="ms-3 text-sm font-semibold text-slate-600 cursor-pointer">Se souvenir de moi</label>
            </div>

            <!-- Bouton -->
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-2xl shadow-sm shadow-indigo-200 transition-all">
                Connexion
            </button>
        </form>

        <p class="text-center mt-8 text-sm font-medium text-slate-500">
            Pas encore de compte ? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">S'inscrire</a>
        </p>
    </div>
</x-guest-layout>