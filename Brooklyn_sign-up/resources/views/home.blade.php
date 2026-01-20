<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 py-20 overflow-hidden">
        <!-- Background car silhouette -->
        <div class="absolute inset-0 opacity-5">
            <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-[800px] h-auto" viewBox="0 0 640 512" fill="currentColor">
                <path d="M171.3 96H224v96H111.3l30.4-75.9C146.5 104 158.2 96 171.3 96zM272 192V96h81.2c9.7 0 18.9 4.4 25 12l67.2 84H272zm256.2 1L428.2 68c-18.2-22.8-45.8-36-75-36H171.3c-39.3 0-74.6 23.9-89.1 60.3L40.6 196.4C16.8 205.8 0 228.9 0 256v112c0 17.7 14.3 32 32 32h33.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80h130.6c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H608c17.7 0 32-14.3 32-32v-48c0-65.2-48.3-119-111-127z"/>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left animate-fade-in-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                        Welkom bij Brooklyn Drive
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-700 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                        Iedereen mobiel, op een duurzame manier
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start animate-fade-in-up" style="animation-delay: 0.3s;">
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
                <div class="relative animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="relative">
                        <img src="{{ asset('assets/homepage/hero-car.jpg') }}" alt="Elektrische lesauto" class="rounded-2xl shadow-2xl w-full">
                        <div class="absolute -bottom-4 -right-4 bg-eisgroen text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="font-semibold">100% Elektrisch</span>
                        </div>
                    </div>
                    <!-- Small car thumbnails -->
                    <div class="absolute -left-8 top-1/2 -translate-y-1/2 hidden lg:flex flex-col gap-3">
                        <img src="{{ asset('assets/homepage/thumb-1.jpg') }}" alt="Lesauto" class="w-20 h-14 object-cover rounded-lg shadow-md border-2 border-white hover:scale-110 transition-transform cursor-pointer">
                        <img src="{{ asset('assets/homepage/thumb-2.jpg') }}" alt="Lesauto" class="w-20 h-14 object-cover rounded-lg shadow-md border-2 border-white hover:scale-110 transition-transform cursor-pointer">
                        <img src="{{ asset('assets/homepage/thumb-3.jpg') }}" alt="Lesauto" class="w-20 h-14 object-cover rounded-lg shadow-md border-2 border-white hover:scale-110 transition-transform cursor-pointer">
                    </div>
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
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Onze Visie
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Bij Brooklyn Drive geloven we dat iedereen mobiel moet kunnen zijn, maar wel op een duurzame manier. 
                        Daarom rijden we met elektrische auto's en innoveren we met nieuwe vormen van mobiliteit!
                    </p>
                    <ul class="space-y-3">
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
                            Moderne, stille auto's
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-eisgroen mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Toekomstgericht rijden
                        </li>
                    </ul>
                </div>
                <div class="relative animate-on-scroll" style="animation-delay: 0.2s;">
                    <img src="{{ asset('assets/homepage/driving-lesson.jpg') }}" alt="Rijles in actie" class="rounded-2xl shadow-2xl w-full">
                    <div class="absolute -bottom-6 -left-6 bg-eisblue text-white p-4 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold">4</div>
                        <div class="text-sm">Ervaren instructeurs</div>
                    </div>
                </div>
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
    <div class="py-20 bg-gray-900 relative overflow-hidden">
        <!-- Background car image -->
        <div class="absolute inset-0">
            <img src="{{ asset('assets/homepage/cta-background.jpg') }}" alt="" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/95 to-gray-900/80"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left animate-on-scroll">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                        Klaar om te beginnen?
                    </h2>
                    <p class="text-lg text-gray-300 mb-8">
                        Meld je aan en start vandaag nog met je rijlessen bij Brooklyn Drive. 
                        Samen maken we jou een veilige en duurzame bestuurder!
                    </p>
                    @guest
                        <a href="{{ route('register') }}" class="inline-block bg-eisgeel hover:bg-eisgeel/90 text-gray-900 font-bold px-10 py-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                            Registreer Nu
                        </a>
                    @endguest
                </div>
                <div class="hidden md:block animate-on-scroll" style="animation-delay: 0.2s;">
                    <div class="grid grid-cols-2 gap-4">
                        <img src="{{ asset('assets/homepage/gallery-1.jpg') }}" alt="Onze lesauto" class="rounded-lg shadow-lg hover:scale-105 transition-transform">
                        <img src="{{ asset('assets/homepage/gallery-2.jpg') }}" alt="Elektrisch rijden" class="rounded-lg shadow-lg hover:scale-105 transition-transform mt-8">
                    </div>
                </div>
            </div>
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
