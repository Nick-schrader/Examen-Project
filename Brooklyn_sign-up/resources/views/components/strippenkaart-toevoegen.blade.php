@php
    $targetUserId = request('user');
@endphp

<div class="flex items-center justify-center">

    {{-- Knop om strippenkaart toevoegen modal te openen --}}
    <button id="open-strippenkaart"
        class="w-[240px] h-10 bg-eisgeel rounded-md text-lg font-bold text-center flex items-center justify-center hover:bg-yellow-400 cursor-pointer">
        + Strippenkaart toevoegen
    </button>

    {{-- Modal --}}
    <div id="strippenkaart-modal"
         class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">

        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden ml-[20px] mr-[20px]">

            {{-- Header --}}
            <div class="bg-eisblue text-white px-4 py-3 flex items-center justify-between">
                <x-application-logo class="block w-auto h-9 fill-current text-white" />
                <button class="x-button text-white hover:text-red-500 text-3xl leading-none">&times;</button>
            </div>
            {{-- Content --}}
            <div class="p-8 flex flex-col items-center">

                {{-- Titel --}}
                <h2 class="text-2xl font-bold mb-6 text-center text-eisblue">Strippenkaart toevoegen</h2>

                {{-- Gebruiker selecteren --}}
                <x-user-selector :selected-user-id="$targetUserId" modal="strippenkaart" />

                {{-- Korting ophalen component --}}
                <div class="mb-6 w-full">
                    <x-Kortingophalen :selected-user-id="$targetUserId" modal="strippenkaart" />
                </div>

                {{-- LessenAantal --}}
                <div class="w-full flex justify-center mb-6">
                    <x-lessenAantal :selected-user-id="$targetUserId" />
                </div>

                {{-- Strippenkaart toevoegen knoppen --}}
                <div class="flex flex-row gap-6 justify-center w-full">
                    @foreach([15,20,25] as $amount)
                        <form method="POST" action="{{ route('strippenkaart.add') }}">
                            @csrf
                            <input type="hidden" name="amount" value="{{ $amount }}">
                            <input type="hidden" name="user_id" value="{{ $targetUserId }}">
                            <button type="submit"
                                class="strippenkaart-close-button flex w-[60px] h-[60px] bg-eisgeel rounded-md justify-center items-center text-lg font-bold hover:bg-yellow-400">
                                +{{ $amount }}
                            </button>
                        </form>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>