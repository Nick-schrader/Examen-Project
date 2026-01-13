<!-- Agenda Table -->
<div class="flex justify-center w-full mt-6">
<div class="w-full max-w-6xl bg-eisgeel rounded-2xl overflow-hidden shadow-2xl">
<div class="flex">
<div class="flex flex-1 flex-col md:flex-row">
<div class="flex-1">

<!-- Week Navigation -->
<div class="flex flex-col sm:flex-row items-center justify-center bg-eisgeel py-4 border-b gap-2">
    <a href="?week={{ $prev->isoWeek() }}&year={{ $prev->year }}&user={{ $selectedUserId }}" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&lt;</a>

    <span class="mx-6 text-xl font-semibold text-gray-700">
        Week {{ $startOfWeek->format('W') }}
        <span class="ml-2 text-sm text-gray-500">
            ({{ $startOfWeek->format('d M') }} - {{ $startOfWeek->copy()->addDays(5)->format('d M') }})
        </span>
    </span>

    <a href="?week={{ $next->isoWeek() }}&year={{ $next->year }}&user={{ $selectedUserId }}" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&gt;</a>

    <a href="?week={{ now()->isoWeek() }}&year={{ now()->year }}&user={{ $selectedUserId }}" class="ml-4 px-4 py-1 rounded bg-eisgeel text-eisblue font-semibold shadow hover:bg-yellow-300 transition">
        Spring naar deze week
    </a>
</div>

<!-- Days -->
<div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x">

@foreach ($days as $i => $day)
<div class="flex-1">

<div class="bg-eisgeel font-semibold py-2 border-b flex flex-col items-center">
    <span class="block text-base">{{ ucfirst($day->locale('nl')->isoFormat('dddd')) }}</span>
    <span class="block text-xs text-gray-500">{{ $day->format('d-m-Y') }}</span>
</div>

<div class="day-content">

@foreach ($timeBlocks as $time)

@php
$blockLabel = ltrim($time,'0') . '-' . ltrim(sprintf('%02d:00', ((int)substr($time,0,2))+1),'0');

$slotStart = $day->format('Y-m-d') . ' ' . $time;
$slotEnd   = $day->format('Y-m-d') . ' ' . sprintf('%02d:00', ((int)substr($time,0,2))+1);

$slotItems = $items->filter(fn($item) =>
    $item->datum_en_tijd >= $slotStart &&
    $item->datum_en_tijd <  $slotEnd
);
@endphp

<div class="text-center py-3 border-b hover:bg-gray-100 transition cursor-pointer">

    <div class="font-semibold text-sm">{{ $blockLabel }}</div>

    @if($slotItems->count())
        <div class="mt-1 text-xs text-eisblue font-bold">
            Les ingepland
        </div>
    @endif

</div>

@endforeach
</div>
</div>
@endforeach

</div>
</div>
</div>
</div>
