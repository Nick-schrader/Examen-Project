<x-app-layout class="min-h-screen flex flex-col">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 animate-fade-in-up">
                Klanten over Brooklyn Drive
            </h1>
            <p class="text-xl md:text-2xl text-gray-700 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                Lees wat onze leerlingen zeggen over hun rijlessen
            </p>
        </div>
    </div>

    <!-- Main Content (Reviews) -->
    <div class="flex-1">
        <x-leftreviews :reviews="$reviews" />
    </div>

    <!-- CTA Section (always at bottom) -->
    <div class="py-20 bg-gray-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 animate-fade-in-up">
                Deel ook jouw ervaring!
            </h2>
            <p class="text-lg text-gray-300 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                Schrijf een review en help anderen om een goede keuze te maken.
            </p>

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
