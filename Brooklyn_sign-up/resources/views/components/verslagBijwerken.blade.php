@props(['les' => null, 'verslag' => null])

{{-- Geen les geselecteerd melding --}}
@if(!$les)
    <div class="bg-red-50 text-red-700 p-4 rounded">
        Geen les geselecteerd.
    </div>
    @php return; @endphp
@endif

<div class="bg-eisgeel rounded-lg p-4 shadow-sm border border-eisgroen/30">

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Verslag formulier --}}
    <form id="verslag-form" method="POST" action="{{ route('verslag.opslaan') }}">
        @csrf

        <input type="hidden" name="rooster_item_id" value="{{ $les->id }}">

        {{-- Verslag tekstgebied --}}
        <textarea
            name="verslag"
            class="bg-white border border-gray-300 rounded-md w-full p-2 resize-none"
            rows="10"
        >{{ $verslag->verslag ?? '' }}</textarea>
    </form>

    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        {{-- Verslag opslaan knop --}}
        <button type="submit"
                form="verslag-form"
                class="bg-eisgroen text-white px-4 py-2 rounded shadow hover:bg-[#a3b97f] transition w-full sm:w-auto">
            {{ $verslag ? 'Verslag bijwerken' : 'Verslag opslaan' }}
        </button>

        {{-- Verslag verwijderen knop --}}
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