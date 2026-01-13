@php
    $datum_en_tijd = is_array($lesson) ? $lesson['datum_en_tijd'] : $lesson->datum_en_tijd;
    $auto = is_array($lesson) ? $lesson['auto'] : $lesson->auto;
    $dt = \DateTime::createFromFormat('d/m/Y H:i:s', $datum_en_tijd);
@endphp

<div x-data="{ showPopUp: false }">
    <a href="#" @click.prevent="showPopUp = true" class="flex flex-col justify-between bg-eisgeel border border-eisblue rounded-2xl shadow-2xl p-6 min-w-[0] max-w-full transition-transform hover:scale-[1.02]">
        <div class="flex items-center gap-4 mb-4">
        <div class="flex flex-col items-center justify-center bg-eisblue/10 rounded-xl px-4 py-2 min-w-[90px]">
            <span class="text-2xl font-bold leading-none text-eisblue">
                {{ $dt ? $dt->format('d') : '' }}
            </span>
            <span class="text-xs tracking-widest uppercase text-eisblue">
                {{ $dt ? $dt->format('M Y') : '' }}
            </span>
        </div>
        <div class="flex flex-col flex-1">
            <span class="text-lg font-semibold text-eisblue">
                {{ $dt ? $dt->format('l') : '' }}
            </span>
            <span class="text-base text-gray-700">
                {{ $dt ? $dt->format('H:i') : '' }} uur
            </span>
        </div>
    </div>
        <div class="flex items-center justify-between mt-2">
            <span class="inline-block px-4 py-2 text-base font-medium rounded-lg text-eisblue bg-eisgeel">Auto: {{ optional(DB::table('auto')->where('id', $auto)->first())->kenteken ?? 'Onbekend' }}</span>
        </div>
    </a>
    <div x-show="showPopUp" @close="showPopUp = false" style="display: none;">
        <x-rooster.pop-up :lesson="$lesson" />
    </div>
</div>
