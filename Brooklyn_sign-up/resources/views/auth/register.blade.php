<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Naam -->
        <div>
            <x-input-label for="naam" :value="__('Naam')" />
            <x-text-input id="naam" class="block w-full mt-1" type="text" name="naam" :value="old('naam')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('naam')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telefoon -->
        <div class="mt-4">
            <x-input-label for="telefoon" :value="__('Telefoon')" />
            <x-text-input id="telefoon" class="block w-full mt-1" type="text" name="telefoon" :value="old('telefoon')" required />
            <x-input-error :messages="$errors->get('telefoon')" class="mt-2" />
        </div>

        <!-- Geboorte datum -->
        <div class="mt-4">
            <x-input-label for="geboorte_datum" :value="__('Geboorte datum')" />
            <x-text-input id="geboorte_datum" class="block w-full mt-1" type="date" name="geboorte_datum" :value="old('geboorte_datum')" required />
            <x-input-error :messages="$errors->get('geboorte_datum')" class="mt-2" />
        </div>

        <!-- Geslacht slider -->
        <div class="mt-4">
            <x-input-label for="geslacht" :value="__('Geslacht')" />

            <div class="flex mt-2">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="geslacht" value="man" class="sr-only peer" required
                        {{ old('geslacht') === 'man' ? 'checked' : '' }} />
                    <div class="px-4 py-2 text-center border rounded-l-lg border-gray-300 peer-checked:bg-blue-500 peer-checked:text-white">
                        Man
                    </div>
                </label>

                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="geslacht" value="vrouw" class="sr-only peer"
                        {{ old('geslacht') === 'vrouw' ? 'checked' : '' }} />
                    <div class="px-4 py-2 text-center border rounded-r-lg border-gray-300 peer-checked:bg-pink-500 peer-checked:text-white">
                        Vrouw
                    </div>
                </label>
            </div>

            <x-input-error :messages="$errors->get('geslacht')" class="mt-2" />
        </div>


        <!-- Adres (opgebouwd uit 4 velden) -->
        <div class="mt-4">
            <x-input-label :value="__('Adres')" />
            <div class="flex flex-wrap gap-2 mt-1">
                <x-text-input type="text" name="straat" placeholder="Straat" class="flex-1 min-w-[120px]" required />
                <x-text-input type="text" name="huisnummer" placeholder="Huisnummer" class="flex-[0_0_100px]" required />
                <x-text-input type="text" name="postcode" placeholder="Postcode" class="flex-[0_0_120px]" required />
                <x-text-input type="text" name="stad" placeholder="Stad" class="flex-1 min-w-[120px]" required />
            </div>

            <!-- Errors should be inside the main address div -->
            <x-input-error :messages="$errors->get('straat')" class="mt-2" />
            <x-input-error :messages="$errors->get('huisnummer')" class="mt-2" />
            <x-input-error :messages="$errors->get('postcode')" class="mt-2" />
            <x-input-error :messages="$errors->get('stad')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Wachtwoord')" />
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Bevestig wachtwoord')" />
            <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!--Als je al een account hebt kan je hier op drukken om naar inlog pagina te gaan-->
        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Al geregistreerd?') }}
            </a>

<x-primary-button class="ms-4 bg-eisgroen hover:bg-eisgroen/80 text-white">
    {{ __('Register') }}
</x-primary-button>

        </div>
    </form>
</x-guest-layout>
