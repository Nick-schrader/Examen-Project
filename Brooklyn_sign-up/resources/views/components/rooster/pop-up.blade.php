<?php
    $verslag = $lesson->verslag;
    $instructeur = $lesson->instructeur;
    $auto = $lesson->autoItem;
?>

<div class="fixed inset-0 flex items-center justify-center z-[70] bg-black/40">
    <div class="relative w-full max-w-xl p-10 bg-white border-2 shadow-2xl rounded-3xl border-eisblue">
        <!-- Alpine.js Close Button -->
        <button type="button"
            class="absolute text-3xl font-bold text-gray-400 top-4 right-4 hover:text-eisblue focus:outline-none"
            @click="$dispatch('close')"
            aria-label="Sluiten">
            &times;
        </button>
        <div class="flex flex-col items-center gap-6">
            <h3 class="mb-4 text-3xl font-extrabold tracking-wide text-eisblue drop-shadow">Lesdetails</h3>
            <div class="w-full space-y-4 text-lg text-left text-gray-800" x-data="{ showEditPopup: false }">
                <div><span class="text-xl font-semibold text-eisblue">Datum & tijd:</span> <span class="text-lg text-gray-900">{{ $lesson->datum_en_tijd }}</span></div>
                <div><span class="text-xl font-semibold text-eisblue">Instructeur:</span> <span class="text-lg text-gray-900">{{ $instructeur->naam ?? 'Onbekend' }}</span> <span class="text-base text-gray-500">({{ $instructeur->telefoon ?? '' }})</span></div>
                <div><span class="text-xl font-semibold text-eisblue">Auto:</span> <span class="text-lg text-gray-900">{{ $auto->merk ?? 'Onbekend' }}</span> <span class="text-base text-gray-500">({{ $auto->kenteken ?? '' }})</span></div>
                <div>
                    <x-rooster.button @click.prevent="showEditPopup = true">
                        Aanpassen
                    </x-rooster.button>
                </div>
                <div>
                    <x-rooster.button class="bg-eisgroen">
                        Verwijder
                    </x-rooster.button>
                </div>
                <div style="display: none" x-show="showEditPopup" @close="showEditPopup = false">
                    <x-rooster.edit :add="true" :info="$lesson" />
                </div>
                @if ($verslag)
                    <div class="mt-6">
                        <p class="mb-2 text-2xl font-bold text-eisblue">Verslag</p>
                        <div class="p-4 text-lg text-gray-900 whitespace-pre-line border shadow-inner bg-eisgeel/40 rounded-xl border-eisblue/20">{{ $verslag->verslag }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
