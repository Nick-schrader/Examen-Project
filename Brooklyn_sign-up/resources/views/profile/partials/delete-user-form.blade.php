<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Verwijder account
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Zodra je account is verwijderd, worden al je gegevens en data permanent verwijderd. Download eerst alles wat je wilt bewaren.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Verwijder account</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Weet je zeker dat je je account wilt verwijderen?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Zodra je account is verwijderd, worden al je gegevens en data permanent verwijderd. Vul je wachtwoord in om te bevestigen dat je je account definitief wilt verwijderen.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Wachtwoord" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-3/4 mt-1"
                    placeholder="Wachtwoord"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuleren
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Verwijder account
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
