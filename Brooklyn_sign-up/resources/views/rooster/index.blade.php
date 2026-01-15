<x-app-layout x-data="{ showPopUp: false }">
    <div class="fixed z-[40] flex gap-6 font-semibold text-white right-4 bottom-4 md:top-20 md:bottom-auto md:right-10">
        <x-rooster.button href="/rooster{{ $history ? '' : '/history' }}">
            {{ $history ? 'Toon huidig rooster' : 'Toon geschiedenis' }}
        </x-rooster.button>
        <x-rooster.button href="#" @click.prevent="showPopUp = true" >
            Les inplannen
        </x-rooster.button>
    </div>

    <x-rooster.week :rooster="$rooster"/>

    <div style="display: none" x-show="showPopUp" @close="showPopUp = false">
        <x-rooster.edit :add="true" />
    </div>
</x-app-layout>

<script>

</script>
