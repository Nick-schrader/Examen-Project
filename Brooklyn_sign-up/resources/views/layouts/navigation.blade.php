<nav x-data="{ open: false }" class="border-b border-gray-100 bg-eisblue">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ Auth::check() ? route('dashboard') : '/home' }}">
                        <x-application-logo class="block w-auto text-gray-800 fill-current h-9" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @guest
                        <x-nav-link :href="'/home'" :active="request()->is('home')">
                            Home
                        </x-nav-link>
                        <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                            Contact
                        </x-nav-link>
                    @endguest
                    
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Home
                        </x-nav-link>
                        <x-nav-link :href="route('review')" :active="request()->routeIs('review')">
                            Reviews
                        </x-nav-link>
                        <x-nav-link :href="route('overOns')" :active="request()->routeIs('overOns')">
                            Over ons
                        </x-nav-link>
                        <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                            Contact
                        </x-nav-link>

                        @if(Auth::user()->type === 2 || Auth::user()->type === 3)
                            <x-nav-link :href="route('agenda')" :active="request()->routeIs('agenda')">
                                Agenda
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->type === 3)
                            <x-nav-link :href="route('Beheer')" :active="request()->routeIs('Beheer')">
                                Beheer
                            </x-nav-link>
                        @elseif(Auth::user()->type === 1)
                            <x-nav-link :href="route('rooster.get')" :active="request()->routeIs('rooster.get')">
                                Rooster
                            </x-nav-link>
                        @elseif(Auth::user()->type === 0)
                            <x-nav-link :href="route('wordLeerling')" :active="request()->routeIs('wordLeerling')">
                                Word leerling
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->isEigenaar())
                            <x-nav-link :href="route('wagenpark')" :active="request()->routeIs('wagenpark')">
                                Wagenpark
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Settings Dropdown for Authenticated Users -->
                    <x-dropdown align="center" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 pt-1 pb-2 h-[64px] text-sm leading-4 font-medium bg-eisblue border-b-2 border-transparent text-gray-400 hover:text-white hover:border-white focus:outline-none transition ease-in-out duration-150"
                            >
                                <div>{{ Auth::user()->naam }}</div>
                                <div class="ms-1">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Account
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Uitloggen
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <!-- Login and Register Links for Guests -->
                    <div class="flex space-x-4">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            Log in
                        </x-nav-link>
                        @if(Route::has('register'))
                            <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                                Register
                            </x-nav-link>
                        @endif
                    </div>
                @endguest
            </div>

            <!-- Hamburger for Mobile -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-black transition duration-150 ease-in-out bg-gray-100 rounded-md hover:text-black focus:outline-none">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @guest
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="'/home'" :active="request()->is('home')">
                    Home
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                    Contact
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    Log in
                </x-responsive-nav-link>
                @if(Route::has('register'))
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        Register
                    </x-responsive-nav-link>
                @endif
            </div>
        @endguest
        
        @auth
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Home
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('review')" :active="request()->routeIs('review')">
                    Reviews
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('overOns')" :active="request()->routeIs('overOns')">
                    Over ons
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                    Contact
                </x-responsive-nav-link>

                @if(Auth::user()->type === 2 || Auth::user()->type === 3)
                    <x-responsive-nav-link :href="route('agenda')" :active="request()->routeIs('agenda')">
                        Agenda
                    </x-responsive-nav-link>
                @elseif(Auth::user()->type === 1)
                    <x-responsive-nav-link :href="route('rooster.get')" :active="request()->routeIs('rooster.get')">
                        Rooster
                    </x-responsive-nav-link>
                @elseif(Auth::user()->type === 0)
                    <x-responsive-nav-link :href="route('wordLeerling')" :active="request()->routeIs('wordLeerling')">
                        Word leerling
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isEigenaar())
                    <x-responsive-nav-link :href="route('wagenpark')" :active="request()->routeIs('wagenpark')">
                        Wagenpark
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('Beheer')" :active="request()->routeIs('Beheer')">
                        Beheer
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options for Authenticated Users -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-400">{{ Auth::user()->naam }}</div>
                    <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Account
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Uitloggen
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>