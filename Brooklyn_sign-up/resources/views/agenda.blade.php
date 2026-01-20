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

@if(Auth::user()->type == 3 && !request('user'))
    <div class="mt-10 text-gray-500 text-lg text-center">
        Selecteer eerst een instructeur om de bijbehorende agenda te bekijken.
    </div>
@else
    <x-agenda 
        :lessen="$lessen" 
        :start-of-week="$startOfWeek" 
        :prev="$prev" 
        :next="$next" 
        :days="$days" 
        :time-blocks="$timeBlocks"
        :selected-user-id="$selectedUserId"
        :verslag="$verslag"
        :les="$les" />
@endif

</div>
</x-app-layout>