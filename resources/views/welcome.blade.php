<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MiniLMS') }} - L'apprentissage par l'IA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Lecteur Lottie -->
   <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-lms-beige text-lms-dark font-sans selection:bg-lms-red selection:text-white">

    <!-- REQUÊTES BASE DE DONNÉES -->
    @php
        // 1. Les 3 formations mises en avant
        $formationsPhares = \App\Models\Formation::with('chapitres.sousChapitres')
            ->where('is_public', true)
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        // 2. Statistiques réelles
        $statsLecons = \App\Models\SousChapitre::count();
        $statsQuestions = \App\Models\Question::count();
        $statsApprenants = \App\Models\User::where('role', 'apprenant')->count();
        $statsEvaluations = \App\Models\Note::count();
    @endphp

    <!-- NAVIGATION PUBLIQUE -->
    <nav class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
        <a href="/" class="flex items-center">
            <span class="font-pixel text-3xl font-black text-lms-dark tracking-wider">
                MINI<span class="text-lms-red">LMS</span>
            </span>
        </a>

        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-lms-dark hover:text-lms-red transition">Accéder à mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-lms-dark transition">Connexion</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-lms-dark text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-black transition shadow-md">S'inscrire</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- HERO SECTION avec Carrousel de texte (Alpine.js) -->
    <main x-data="{ 
            activeSlide: 0, 
            slides: [
                'Des parcours de formation générés sur mesure pour répondre précisément à vos objectifs d\'apprentissage et de montée en compétences.',
                'Validez vos acquis instantanément grâce à des évaluations intelligentes et des QCM interactifs à la fin de chaque module.',
                'Un accompagnement complet avec un tableau de bord intuitif pour suivre votre progression et cibler vos axes d\'amélioration.'
            ] 
        }" 
        x-init="setInterval(() => activeSlide = (activeSlide === 2 ? 0 : activeSlide + 1), 5000)"
        class="relative w-full h-[600px] flex items-center">

        <!-- Image de fond avec filtre sombre pour garantir la lisibilité du texte -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1920&auto=format&fit=crop" 
                 alt="Background" 
                 class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-lms-dark/80"></div>
        </div>

        <!-- Contenu superposé -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
            <div class="max-w-3xl">
                
                <h1 class="text-5xl md:text-7xl text-white font-black tracking-wide leading-tight mb-8">
                    <span class="font-pixel text-lms-red text-4xl md:text-5xl align-middle">Générez</span> 
                    vos cours sur mesure.
                </h1>
                
                <div class="h-24 md:h-20 mb-8 relative">
                    <template x-for="(slide, index) in slides" :key="index">
                        <p x-show="activeSlide === index"
                           x-transition:enter="transition ease-out duration-500"
                           x-transition:enter-start="opacity-0 translate-y-4"
                           x-transition:enter-end="opacity-100 translate-y-0"
                           x-transition:leave="transition ease-in duration-300 absolute top-0"
                           x-transition:leave-start="opacity-100"
                           x-transition:leave-end="opacity-0"
                           class="text-lg md:text-xl text-gray-200 font-medium leading-relaxed"
                           x-text="slide">
                        </p>
                    </template>
                </div>

                <div class="flex items-center gap-3 mb-10">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index" 
                                class="h-1.5 transition-all duration-300 rounded"
                                :class="activeSlide === index ? 'w-8 bg-lms-red' : 'w-4 bg-gray-500 hover:bg-gray-400'">
                        </button>
                    </template>
                </div>

                <div>
                    <a href="{{ route('register') }}" class="btn-primaire inline-block">
                        Commencer l'expérience
                    </a>
                </div>

            </div>
        </div>
    </main>

    <!-- SECTION 1 : LA MÉTHODE (Effet Parallaxe 3D) -->
    <section class="bg-white py-32 border-t border-gray-200 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-black text-lms-dark mb-20 text-center tracking-wide">
                <span class="font-pixel text-lms-red text-2xl md:text-3xl align-middle">Comment</span> ça fonctionne ?
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                
                <!-- Étape 1 -->
                <div x-data="{ rotateX: 0, rotateY: 0 }"
                     @mousemove="const rect = $el.getBoundingClientRect(); rotateY = (($event.clientX - rect.left) / rect.width - 0.5) * 20; rotateX = (($event.clientY - rect.top) / rect.height - 0.5) * -20;"
                     @mouseleave="rotateX = 0; rotateY = 0"
                     :style="`transform: perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg);`"
                     class="relative bg-lms-beige p-10 rounded border border-gray-200 transition-transform duration-200 ease-out flex flex-col h-full cursor-default shadow-sm hover:shadow-xl hover:border-lms-red/50">
                    <div class="absolute -top-6 -right-2 font-pixel text-[120px] text-lms-dark/5 select-none pointer-events-none">1</div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-lms-dark text-white rounded flex items-center justify-center font-pixel text-xl mb-8">1</div>
                        <h3 class="text-xl font-bold mb-4 text-lms-dark">Création sur mesure</h3>
                        <p class="text-gray-600 leading-relaxed">Définissez vos objectifs. Notre moteur intelligent structure un plan de formation complet avec des leçons détaillées et adaptées à votre niveau.</p>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div x-data="{ rotateX: 0, rotateY: 0 }"
                     @mousemove="const rect = $el.getBoundingClientRect(); rotateY = (($event.clientX - rect.left) / rect.width - 0.5) * 20; rotateX = (($event.clientY - rect.top) / rect.height - 0.5) * -20;"
                     @mouseleave="rotateX = 0; rotateY = 0"
                     :style="`transform: perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg);`"
                     class="relative bg-lms-beige p-10 rounded border border-gray-200 transition-transform duration-200 ease-out flex flex-col h-full cursor-default shadow-sm hover:shadow-xl hover:border-lms-red/50 mt-0 md:mt-12">
                    <div class="absolute -top-6 -right-2 font-pixel text-[120px] text-lms-red/5 select-none pointer-events-none">2</div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-lms-red text-white rounded flex items-center justify-center font-pixel text-xl mb-8">2</div>
                        <h3 class="text-xl font-bold mb-4 text-lms-dark">Apprentissage actif</h3>
                        <p class="text-gray-600 leading-relaxed">Lisez vos cours dans une interface épurée, conçue pour la concentration. Le contenu va droit au but, sans digressions inutiles.</p>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div x-data="{ rotateX: 0, rotateY: 0 }"
                     @mousemove="const rect = $el.getBoundingClientRect(); rotateY = (($event.clientX - rect.left) / rect.width - 0.5) * 20; rotateX = (($event.clientY - rect.top) / rect.height - 0.5) * -20;"
                     @mouseleave="rotateX = 0; rotateY = 0"
                     :style="`transform: perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg);`"
                     class="relative bg-lms-beige p-10 rounded border border-gray-200 transition-transform duration-200 ease-out flex flex-col h-full cursor-default shadow-sm hover:shadow-xl hover:border-lms-red/50">
                    <div class="absolute -top-6 -right-2 font-pixel text-[120px] text-lms-dark/5 select-none pointer-events-none">3</div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white border-2 border-lms-dark text-lms-dark rounded flex items-center justify-center font-pixel text-xl mb-8">3</div>
                        <h3 class="text-xl font-bold mb-4 text-lms-dark">Validation des acquis</h3>
                        <p class="text-gray-600 leading-relaxed">Testez vos connaissances à la fin de chaque chapitre avec des évaluations générées dynamiquement pour cibler vos points faibles.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2 : APERÇU DU CATALOGUE (Dynamique) -->
    <section class="bg-lms-dark py-32 border-t border-gray-800" x-data="{ activeTab: 1 }">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-24 gap-6">
                <h2 class="text-3xl md:text-5xl font-black text-white tracking-wide">
                    <span class="font-pixel text-lms-red text-2xl md:text-4xl align-middle">Nos</span> formations phares
                </h2>
                <a href="{{ route('formations.index') }}" class="text-sm font-bold text-gray-400 hover:text-lms-red transition-colors border-b-2 border-gray-700 hover:border-lms-red pb-1">
                    Voir tout le catalogue
                </a>
            </div>
            
            @if($formationsPhares->count() > 0)
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center">
                
                <!-- COLONNE GAUCHE : La liste interactive -->
                <div class="w-full lg:w-1/2 flex flex-col">
                    @foreach($formationsPhares as $index => $formation)
                        <button @mouseenter="activeTab = {{ $index + 1 }}" class="text-left group relative py-8 border-b border-gray-800 transition-all duration-300">
                            <div class="flex items-center gap-6">
                                <span class="font-pixel text-sm transition-colors duration-300" :class="activeTab === {{ $index + 1 }} ? 'text-lms-red' : 'text-gray-700'">0{{ $index + 1 }}</span>
                                <h3 class="text-2xl md:text-4xl font-bold transition-all duration-500 line-clamp-1" :class="activeTab === {{ $index + 1 }} ? 'text-white translate-x-4' : 'text-gray-600 group-hover:text-gray-400'">
                                    {{ $formation->nom }}
                                </h3>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- COLONNE DROITE : L'affichage dynamique avec images -->
                <!-- COLONNE DROITE : L'affichage dynamique avec les images adaptées -->
<div class="w-full lg:w-1/2 relative h-[450px]">
    @php
        // Tableau associant chaque formation à son image spécifique
        $unsplashImages = [
            0 => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?q=80&w=800&auto=format&fit=crop', // Python
            1 => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=800&auto=format&fit=crop', // UI/UX
            2 => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop'  // Analyse de données
        ];
    @endphp

    @foreach($formationsPhares as $index => $formation)
        <div x-show="activeTab === {{ $index + 1 }}" {{ $index > 0 ? 'x-cloak' : '' }}
             x-transition:enter="transition ease-out duration-500 delay-100"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300 absolute inset-0"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-black rounded border border-gray-800 w-full h-full flex flex-col overflow-hidden shadow-[0_0_50px_rgba(211,47,47,0.1)]">
            
            <a href="{{ route('formations.show', $formation->id) }}" class="block w-full h-full flex flex-col group">
                <div class="h-1/2 relative overflow-hidden border-b border-gray-800">
                    <!-- Utilisation de l'image correspondante selon l'index -->
                    <img src="{{ $unsplashImages[$index] ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop' }}" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-85 transition-opacity" 
                         alt="{{ $formation->nom }}">
                    
                    <div class="absolute top-6 left-6 bg-lms-beige text-lms-dark text-xs font-bold px-4 py-2 rounded uppercase tracking-widest z-10">
                        {{ $formation->niveau ?? 'Général' }}
                    </div>
                </div>
                
                <div class="p-8 flex-1 flex flex-col justify-between relative z-10">
                    <p class="text-gray-400 text-lg leading-relaxed line-clamp-3">{{ $formation->description }}</p>
                    
                    @php
                        $leconCount = $formation->chapitres->sum(fn($c) => $c->sousChapitres->count());
                    @endphp
                    
                    <div class="border-t border-gray-800 pt-6 flex justify-between items-center text-sm font-bold text-gray-500">
                        <span class="text-lms-beige">{{ $leconCount }} Leçon(s)</span>
                        <span class="text-lms-red group-hover:translate-x-2 transition-transform">Découvrir →</span>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
            </div>
            @endif
        </div>
    </section>

    <!-- SECTION 3 : STATISTIQUES (Dynamiques) -->
    <section class="bg-lms-beige py-24 border-t border-gray-200 relative overflow-hidden">
        <x-pattern class="absolute inset-0" color="#1C1B1A" opacity="0.04" size="24px" />
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                
                <div class="flex flex-col items-center">
                    <div class="font-pixel text-4xl md:text-5xl text-lms-red mb-4">{{ $statsLecons }}</div>
                    <div class="text-lms-dark font-black text-sm uppercase tracking-widest">Leçons générées</div>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="font-pixel text-4xl md:text-5xl text-lms-red mb-4">{{ $statsQuestions }}</div>
                    <div class="text-lms-dark font-black text-sm uppercase tracking-widest">Questions créées</div>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="font-pixel text-4xl md:text-5xl text-lms-red mb-4">{{ $statsApprenants }}</div>
                    <div class="text-lms-dark font-black text-sm uppercase tracking-widest">Apprenants inscrits</div>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="font-pixel text-4xl md:text-5xl text-lms-red mb-4">{{ $statsEvaluations }}</div>
                    <div class="text-lms-dark font-black text-sm uppercase tracking-widest">Évaluations passées</div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4 : APPEL À L'ACTION FINAL -->
    <section class="bg-white py-32 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-black text-lms-dark mb-6 tracking-wide">
                <span class="font-pixel text-lms-red text-2xl md:text-4xl align-middle">Prêt</span> à démarrer ?
            </h2>
            <p class="text-lg text-gray-600 font-medium mb-10 leading-relaxed">
                Rejoignez notre plateforme et commencez à apprendre de nouvelles compétences dès aujourd'hui. L'inscription prend moins d'une minute.
            </p>
            <a href="{{ route('register') }}" class="btn-primaire inline-block">
                Créer un compte gratuit
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-lms-dark text-white py-12 text-center">
        <p class="text-sm text-gray-400 font-bold tracking-widest uppercase">© {{ date('Y') }} MiniLMS. Projet éducatif propulsé par l'IA.</p>
    </footer>

</body>
</html>