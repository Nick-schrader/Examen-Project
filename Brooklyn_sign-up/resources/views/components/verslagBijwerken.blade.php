<div class="bg-eisgeel rounded-lg p-4 shadow-sm border border-eisgroen/30">

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- OPSLAAN / BIJWERKEN --}}
    <form id="verslag-form" method="POST" action="{{ route('verslag.opslaan') }}">
        @csrf

        <input type="hidden" name="rooster_item_id" value="{{ $les->id }}">

        <textarea
            name="verslag"
            class="bg-white border border-gray-300 rounded-md w-full p-2 resize-none"
            rows="10"
            placeholder="Voer hier het verslag in..."
        >{{ $verslag->verslag ?? '' }}</textarea>
    </form>

    {{-- KNOPPEN --}}
    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        {{-- OPSLAAN --}}
        <button type="submit"
                form="verslag-form"
                class="bg-eisgroen text-white px-4 py-2 rounded shadow hover:bg-[#a3b97f] transition w-full sm:w-auto">
            {{ $verslag ? 'Verslag bijwerken' : 'Verslag opslaan' }}
        </button>

        {{-- VERWIJDEREN --}}
        @if($verslag)
            <form method="POST" action="{{ route('verslag.verwijderen') }}" class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="rooster_item_id" value="{{ $les->id }}">
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 transition w-full sm:w-auto">
                    Verwijderen
                </button>
            </form>
        @endif

    </div>

</div>
