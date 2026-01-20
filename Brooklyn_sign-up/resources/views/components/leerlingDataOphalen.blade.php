@props(['les' => null, 'verslag' => null])

<div class="pt-4 flex flex-col gap-3 text-eisblue">

    @if($les)

        <div class="bg-eisgeel rounded-lg p-4 shadow-sm border border-eisgroen/30">
            <h2 class="text-lg font-semibold text-eisblue mb-3">Lesinformatie</h2>

            <div class="flex flex-col gap-2 text-sm">

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Leerling:</span>
                    <span>{{ $les->leerling_naam }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Adres:</span>
                    <span>{{ $les->adres }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Telefoon:</span>
                    <span>{{ $les->telefoon }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Auto:</span>
                    <span>{{ $les->auto_merk }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Kenteken:</span>
                    <span>{{ $les->kenteken }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Datum:</span>
                    <span>{{ $les->datum }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="font-semibold text-eisblue">Tijd:</span>
                    <span>{{ $les->tijd }}</span>
                </div>

            </div>
        </div>

        <div class="flex justify-center mt-6 bg-eisgroen text-white rounded-md p-4 shadow-md hover:bg-[#a3b97f] cursor-pointer">
            <a href="?modal=verslag&les_id={{ $les->id }}&week={{ request('week') }}&year={{ request('year') }}"
               class="block w-full text-center">
                {{ $verslag ? 'Verslag bijwerken' : 'Verslag toevoegen' }}
            </a>
        </div>

    @else

        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4 text-sm shadow-sm">
            Geen les ingepland op dit tijdstip.
        </div>

    @endif

</div>

