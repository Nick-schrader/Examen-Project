@php
    $targetUserId = request('user');
@endphp

<div class="flex items-center justify-center">

    {{-- Knop om verslagen overzicht te openen --}}
    <button id="open-verslagen"
        class="w-[240px] h-10 bg-eisgeel rounded-md text-lg font-bold text-center flex items-center justify-center hover:bg-yellow-400 cursor-pointer">
        Verslagen bekijken
    </button>

    {{-- Modal --}}
    <div id="verslagen-modal"
         class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">

        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden ml-[20px] mr-[20px]">

            {{-- Header --}}
            <div class="bg-eisblue text-white px-4 py-3 flex items-center justify-between">
                <x-application-logo class="block w-auto h-9 fill-current text-white" />
                <button class="x-button text-white hover:text-red-500 text-3xl leading-none">&times;</button>
            </div>
            {{-- Content --}}
            <div class="p-8 flex flex-col items-center max-h-[70vh] overflow-y-auto">

                {{-- Titel --}}
                <h2 class="text-2xl font-bold mb-6 text-center text-eisblue">Verslagen overzicht</h2>
                {{-- Gebruiker selecteren --}}
                <x-user-selector :selected-user-id="$targetUserId" modal="verslagen" />
                {{-- LessenAantal --}}
                <div class="w-full flex justify-center">
                    <x-lessenAantal :selected-user-id="$targetUserId" />
                </div>
                <div class="p-6 w-full">
                
                    {{-- Overzicht van alle verslagen voor de geselecteerde gebruiker --}}
                    @if($alleVerslagen->isEmpty())
                        {{-- Geen verslagen gevonden bericht --}}
                        <p class="text-gray-500 text-center">Geen verslagen gevonden voor deze leerling.</p>
                    @else
                        <div class="flex flex-col gap-4">
                        
                            @foreach($alleVerslagen as $v)
                                <form method="GET" class="cursor-pointer"
                                      action="{{ route('agenda') }}">
                                                    
                                    <input type="hidden" name="week" value="{{ $startOfWeek->isoWeek() }}">
                                    <input type="hidden" name="year" value="{{ $startOfWeek->year }}">
                                    <input type="hidden" name="les_id" value="{{ $v->rooster_item_id }}">
                                    <input type="hidden" name="modal" value="verslag">
                                                    
                                    <button type="submit" class="w-full text-left">
                                        <div class="border border-eisgroen/30 rounded-lg p-4 shadow-sm bg-eisgeel hover:bg-yellow-200 transition">
                                            
                                            {{-- Datum van de les --}}
                                            <div class="flex justify-between">
                                                <span class="font-semibold text-eisblue">Datum les:</span>
                                                <span>{{ $v->datum_en_tijd }}</span>
                                            </div>
                                        
                                            {{-- Alleen eerste regel van het verslag --}}
                                            <div class="mt-2">
                                                <span class="font-semibold text-eisblue">Verslag:</span>
                                                <p class="mt-1 text-gray-700 whitespace-pre-line">
                                                    {{ Str::of($v->verslag)->before("\n") }}
                                                </p>
                                            </div>
                                        
                                        </div>
                                    </button>
                                
                                </form>
                            @endforeach

                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>