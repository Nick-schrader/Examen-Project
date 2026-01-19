@php
    $lessons = is_string($rooster) ? json_decode($rooster) : $rooster;
@endphp

<div class="w-full max-w-[1800px] min-w-[370px] px-2 py-8 mx-auto">
    <h2 class="mb-8 text-3xl font-extrabold tracking-tight text-center text-eisblue">Mijn Agenda</h2>
    @if(empty($lessons) || count($lessons) === 0)
    <div class="p-6 text-lg text-center shadow-lg text-eisgroen bg-eisgeel rounded-xl">Je hebt nog geen lessen ingepland.</div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
            @foreach($lessons as $les)
                <x-rooster.day :lesson="$les" :history="$history"/>
            @endforeach
        </div>
    @endif
</div>
