<?php

echo "<script>console.log(" . json_encode($user) . ")</script>";

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Profielinformatie
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Werk je profielinformatie en e-mailadres bij.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="naam" :value="'Naam'" />
            <x-text-input id="naam" name="naam" type="text" class="block w-full mt-1" :value="old('naam', $user->naam)" disabled />
            <x-input-error class="mt-2" :messages="$errors->get('naam')" />
        </div>

        <div>
            <x-input-label for="email" :value="'E-mailadres'" />
            <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        Je e-mailadres is niet geverifieerd.

                        <button form="send-verification" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Klik hier om de verificatie e-mail opnieuw te versturen.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            Er is een nieuwe verificatie link naar je e-mailadres gestuurd.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="telefoon" :value="'Telefoon nummer'" />
            <x-text-input id="telefoon" name="telefoon" type="text" class="block w-full mt-1" :value="old('telefoon', $user->telefoon)" required />
            <x-input-error class="mt-2" :messages="$errors->get('telefoon')" />
        </div>

        <div>
            <x-input-label for="geboorte" :value="'Geboorte datum'" />
            <x-text-input id="geboorte" name="geboorte" type="text" class="block w-full mt-1" :value="old('geboorte', $user->geboorte_datum)" disabled />
            <x-input-error class="mt-2" :messages="$errors->get('geboorte')" />
        </div>

        <div>
            <x-input-label for="geslacht" :value="'Geslacht'" />
            <x-text-input id="geslacht" name="geslacht" type="text" class="block w-full mt-1" :value="old('geslacht', $user->geslacht)" disabled />
            <x-input-error class="mt-2" :messages="$errors->get('geslacht')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Opslaan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Opgeslagen.</p>
            @endif
        </div>
    </form>
</section>
