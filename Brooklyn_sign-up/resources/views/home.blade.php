<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                    Welkom bij Brooklyn Drive
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                    Iedereen mobiel, op een duurzame manier
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style="animation-delay: 0.3s;">
                    @guest
                        <a href="{{ route('register') }}" class="bg-eisblue hover:bg-eisblue/90 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            Word Leerling
                        </a>
                        <a href="{{ route('contact') }}" class="bg-eisgeel hover:bg-eisgeel/90 text-gray-900 font-semibold px-8 py-3 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            Neem Contact Op
                        </a>
                    @endguest
                    @auth
                        @if(Auth::user()->type === 0)
                            <a href="{{ route('wordLeerling') }}" class="bg-eisblue hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">
                                Word Leerling
                            </a>
                        @endif
                        @if(Auth::user()->type === 1)
                            <a href="{{ route('rooster.get') }}" class="bg-eisblue hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">
                                Bekijk je Rooster
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Coming Soon - Fatbike Section (PROMINENT) -->
    <div class="bg-gradient-to-r from-eisgeel via-yellow-200 to-eisgeel py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-float" style="animation-delay: 0s;"></div>
            <div class="absolute bottom-10 right-20 w-40 h-40 bg-white rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/3 w-24 h-24 bg-white rounded-full animate-float" style="animation-delay: 2s;"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <div class="inline-block bg-gray-900 text-eisgeel px-6 py-2 rounded-full font-bold text-sm mb-6 animate-bounce-slow">
                    🚀 NIEUW & UNIEK IN NEDERLAND
                </div>
                <h2 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 animate-scale-in">
                    Binnenkort: Fatbike Lessen!
                </h2>
                <p class="text-xl md:text-2xl text-gray-800 max-w-3xl mx-auto mb-8 font-medium animate-fade-in" style="animation-delay: 0.2s;">
                    Brooklyn Drive wordt een van de eersten in Nederland die fatbike lessen en rijexamens aanbiedt. 
                    De toekomst van mobiliteit begint hier! 🚴
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in" style="animation-delay: 0.4s;">
                    <a href="{{ route('contact') }}" class="bg-gray-900 hover:bg-gray-800 text-eisgeel font-bold px-10 py-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        Houd me op de Hoogte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Onze Visie
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Bij Brooklyn Drive geloven we dat iedereen mobiel moet kunnen zijn, maar wel op een duurzame manier. 
                    Daarom rijden we met elektrische auto's en innoveren we met nieuwe vormen van mobiliteit!
                </p>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-12 animate-on-scroll">
                Waarom Brooklyn Drive?
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 border-t-4 border-eisgroen transform hover:-translate-y-2 animate-on-scroll" style="animation-delay: 0.1s;">
                    <div class="w-16 h-16 bg-eisgroen rounded-full flex items-center justify-center mb-6 transition-transform duration-500 hover:rotate-12 hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Duurzaam Rijden</h3>
                    <p class="text-gray-600">
                        Leer autorijden in onze moderne elektrische auto's en draag bij aan een schonere toekomst.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 border-t-4 border-eisblue transform hover:-translate-y-2 animate-on-scroll" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-eisblue rounded-full flex items-center justify-center mb-6 transition-transform duration-500 hover:rotate-12 hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Flexibel Plannen</h3>
                    <p class="text-gray-600">
                        Plan je lessen gemakkelijk online in via onze applicatie. Annuleren kan tot 24 uur van tevoren.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 border-t-4 border-eisgeel transform hover:-translate-y-2 animate-on-scroll" style="animation-delay: 0.3s;">
                    <div class="w-16 h-16 bg-eisgeel rounded-full flex items-center justify-center mb-6 transition-transform duration-500 hover:rotate-12 hover:scale-110">
                        <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Ervaren Instructeurs</h3>
                    <p class="text-gray-600">
                        Onze vier professionele instructeurs staan klaar om jou te begeleiden naar jouw rijbewijs.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Strippenkaarten Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Strippenkaarten
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Bij Brooklyn Drive werken we met strippenkaarten. Koop een pakket met lessen van tevoren 
                    en plan ze flexibel in wanneer het jou uitkomt. Houd eenvoudig je voortgang bij via je account.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Klaar om te beginnen?
            </h2>
            <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
                Meld je aan en start vandaag nog met je rijlessen bij Brooklyn Drive. 
                Samen maken we jou een veilige en duurzame bestuurder!
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block bg-eisgeel hover:bg- text-gray-900 font-bold px-10 py-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    Registreer Nu
                </a>
            @endguest
        </div>
    </div>

    <style>
        /* CSS for animations  */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes bounceSlow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-scale-in {
            animation: scaleIn 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-bounce-slow {
            animation: bounceSlow 2s ease-in-out infinite;
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
    </style>

    <script>
        // JavaScript for animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</x-app-layout>