<x-app-layout class="min-h-screen flex flex-col">

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 animate-fade-in-up">
                Over Brooklyn Drive
            </h1>
            <p class="text-xl md:text-2xl text-gray-700 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                Wij geloven dat iedereen mobiel moet kunnen zijn, op een duurzame en veilige manier
            </p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="flex-1 py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">

            <!-- Text Column -->
            <div class="animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    Onze Missie
                </h2>
                <p class="text-lg text-gray-700 mb-4">
                    Bij Brooklyn Drive willen we autorijden toegankelijk en duurzaam maken voor iedereen. 
                    Onze focus ligt op elektrische lesauto's, milieuvriendelijk rijden en moderne technologie.
                </p>
                <p class="text-lg text-gray-700 mb-4">
                    Onze instructeurs zijn professioneel, ervaren en gepassioneerd om jou te begeleiden naar een veilige rijervaring.
                </p>
                <ul class="space-y-3 mt-6">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-eisgroen mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Zero-emissie rijlessen
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-eisgroen mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Moderne en stille lesauto's
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-eisgroen mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Toekomstgericht en veilig rijden
                    </li>
                </ul>
            </div>

            <!-- Image Column -->
            <div class="relative animate-on-scroll" style="animation-delay: 0.2s;">
                <img src="{{ asset('assets/homepage/team.jpg') }}" alt="Ons team" class="rounded-2xl shadow-2xl w-full">
                <div class="absolute -bottom-6 -left-6 bg-eisblue text-white p-4 rounded-xl shadow-lg">
                    <div class="text-3xl font-bold">4</div>
                    <div class="text-sm">Ervaren instructeurs</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-gray-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 animate-fade-in-up">
                Klaar om te starten?
            </h2>
            <p class="text-lg text-gray-300 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                Word vandaag nog leerling bij Brooklyn Drive en ervaar duurzaam rijles plezier!
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-eisgeel hover:bg-eisgeel/90 text-gray-900 font-bold px-10 py-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl animate-fade-in-up" style="animation-delay: 0.4s">
                Registreer Nu
            </a>
        </div>
    </div>

    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -100px 0px' };
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, observerOptions);
            document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
        });
    </script>

</x-app-layout>
