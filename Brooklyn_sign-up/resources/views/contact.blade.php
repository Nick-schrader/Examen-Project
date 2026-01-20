<x-app-layout class="min-h-screen flex flex-col">

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 animate-fade-in-up">
                Contact
            </h1>
            <p class="text-xl md:text-2xl text-gray-700 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                Heb je vragen of wil je meer informatie? Neem contact met ons op!
            </p>
        </div>
    </div>

    <!-- Contact Content Section -->
    <div class="flex-1 py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-start">

            <!-- Contact Info Column -->
            <div class="animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    Bereik ons
                </h2>
                <p class="text-lg text-gray-700 mb-4">
                    Onze klantenservice staat klaar om je te helpen met al je vragen over rijlessen, registratie en meer.
                </p>

                <ul class="space-y-4 mt-6">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-6 h-6 text-eisblue mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 11-12 0 6 6 0 0112 0zm-6 6v6m-4-4h8"/>
                        </svg>
                        <span>Adres: Straat 123, 1234 AB Stad</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-6 h-6 text-eisblue mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7-5 7 5v8l-7 5-7-5V8z"/>
                        </svg>
                        <span>Email: info@brooklyndrive.nl</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-6 h-6 text-eisblue mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l1 7h12l1-7h2M5 12v6h14v-6"/>
                        </svg>
                        <span>Telefoon: 012-3456789</span>
                    </li>
                </ul>
            </div>  

            <!-- Image Column -->
            <div class="animate-on-scroll">
                <img src="{{ asset('assets/homepage/team2.jpg') }}" alt="Ons team" class="rounded-2xl shadow-2xl w-full h-full object-cover">
            </div>

        </div>
    </div>

    <!-- CTA Section (altijd onderaan) -->
    <div class="py-20 bg-gray-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 animate-fade-in-up">
                Klaar om te beginnen?
            </h2>
            <p class="text-lg text-gray-300 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                Meld je vandaag nog aan voor rijlessen bij Brooklyn Drive!
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
