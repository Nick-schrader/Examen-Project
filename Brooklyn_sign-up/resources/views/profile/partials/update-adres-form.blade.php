
<section>
	<header>
		<h2 class="text-lg font-medium text-gray-900">
			Adres bijwerken
		</h2>

		<p class="mt-1 text-sm text-gray-600">
			Werk je adresgegevens bij.
		</p>
	</header>

	<form method="post" action="{{ route('profile.adresupdate') }}" class="mt-6 space-y-6">
		@csrf
		@method('patch')

		<div>
			<x-input-label for="straat" :value="'Straat'" />
			<x-text-input id="straat" name="straat" type="text" class="block w-full mt-1" :value="old('straat', $user->straat ?? '')" required />
			<x-input-error class="mt-2" :messages="$errors->get('straat')" />
		</div>

		<div>
			<x-input-label for="huisnummer" :value="'Huisnummer'" />
			<x-text-input id="huisnummer" name="huisnummer" type="text" class="block w-full mt-1" :value="old('huisnummer', $user->huisnummer ?? '')" required />
			<x-input-error class="mt-2" :messages="$errors->get('huisnummer')" />
		</div>

		<div>
			<x-input-label for="postcode" :value="'Postcode'" />
			<x-text-input id="postcode" name="postcode" type="text" class="block w-full mt-1" :value="old('postcode', $user->postcode ?? '')" required />
			<x-input-error class="mt-2" :messages="$errors->get('postcode')" />
		</div>

		<div>
			<x-input-label for="woonplaats" :value="'Woonplaats'" />
			<x-text-input id="woonplaats" name="woonplaats" type="text" class="block w-full mt-1" :value="old('woonplaats', $user->woonplaats ?? '')" required />
			<x-input-error class="mt-2" :messages="$errors->get('woonplaats')" />
		</div>

		<div class="flex items-center gap-4">
			<x-primary-button>Opslaan</x-primary-button>

			@if (session('status') === 'adres-updated')
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
