@php
    $autos = \App\Models\Auto::all();
    $instructeurs = \App\Models\User::where('type', 2)->get();

    $add = isset($add) ? $add : false;
    $info = isset($info) ? $info : null;

    if(!!$info) {
        $DT = explode(' ', $info->datum_en_tijd);
        $datum = $DT[0];
        $tijd = $DT[1];
    }

@endphp

<div x-data="{ show: true }" x-show="show" class="fixed inset-0 flex items-center justify-center z-[80] bg-black/40">
    <div class="relative w-full max-w-xl p-10 bg-white border-2 shadow-2xl rounded-3xl border-eisblue">
        <button type="button"
            class="absolute text-3xl font-bold text-gray-400 top-4 right-4 hover:text-eisblue focus:outline-none"
            @click="show = false"
            aria-label="Sluiten">
            &times;
        </button>
        <form method="POST" action="/rooster">
            @csrf
            @if(!$add)
                @method('PATCH')
            @endif
            <div class="flex flex-col gap-4">
                <x-rooster.form.date :value="$datum ?? null" />
                <x-rooster.form.time :value="$tijd ?? null" />
                <x-rooster.form.select name="instructeur" :value="(is_object($info->instructeur ?? null) ? $info->instructeur->id : null)">
                    @foreach($instructeurs as $instructeur)
                        <option value="{{ $instructeur->id }}" @if((is_object($info->instructeur ?? null) ? $info->instructeur->id : null) == $instructeur->id) selected @endif>{{ $instructeur->naam }}</option>
                    @endforeach
                </x-rooster.form.select>
                <x-rooster.form.select name="auto" :value="(is_object($info->auto ?? null) ? $info->auto->id : null)">
                    @foreach($autos as $auto)
                        <option value="{{ $auto->id }}" @if((is_object($info->auto ?? null) ? $info->auto->id : null) == $auto->id) selected @endif>{{ $auto->merk }} {{ $auto->type === 1 ? '(E.V.)' : '(benzine)' }} ({{ $auto->kenteken }})</option>
                    @endforeach
                </x-rooster.form.select>
                <x-rooster.button type="submit">Verstuur</x-rooster.button>
            </div>
        </form>
    </div>
</div>
