<x-app-layout>
<div class="min-h-screen bg-white flex flex-col items-center py-10">

    <div class="w-full max-w-screen-xl px-[62px]">
        <div class="w-full flex flex-col md:flex-row gap-6 justify-between items-center">

            @if(Auth::user()->type == 3)
                <x-user-selector :selected-user-id="request('user')" />
            @endif

            <div class="flex flex-col gap-5 md:flex-row md:justify-between md:w-full md:max-w-[1155px] {{ Auth::user()->type == 2 ? '' : 'hidden' }}">
                <x-strippenkaart-toevoegen />
                <x-overzichtVerslag :alleVerslagen="$alleVerslagen" :startOfWeek="$startOfWeek" />
            </div>
        </div>
    </div>

    <x-agenda :les="$les" :verslag="$verslag" :lessen="$lessen" :start-of-week="$startOfWeek" :prev="$prev" :next="$next" :days="$days" :time-blocks="$timeBlocks" />

</div>
</x-app-layout>