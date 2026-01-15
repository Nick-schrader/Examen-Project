{{-- View agenda.blade.php --}}

<x-app-layout>
<div class="min-h-screen bg-white flex flex-col items-center py-10">

    <div class="w-full max-w-screen-xl px-[62px]">
        <div class="w-full flex flex-col md:flex-row gap-6 justify-between items-center">


            <!-- User Selector -->
            @if(Auth::user()->type == 3)
                <x-user-selector :selected-user-id="request('user')" />
            @endif


            <div class="flex flex-col gap-[10px] {{ Auth::user()->type == 2 ? '' : 'hidden' }}">
                {{-- <x-lessenAantal /> --}}
                <x-strippenkaart-toevoegen />
            </div>
        </div>
    </div>


    <!-- Agenda Component -->
    <x-agenda />

</div>
</x-app-layout>