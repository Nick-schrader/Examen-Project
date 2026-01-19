@props([
    'lessen' => collect(),
    'startOfWeek',
    'prev',
    'next',
    'days',
    'timeBlocks',
    'selectedUserId'
])

<meta name="user-type" content="{{ auth()->user()->type }}">
<div class="flex justify-center w-full mt-6">
<div class="w-full max-w-6xl bg-eisgeel rounded-2xl overflow-hidden shadow-2xl">
    <div class="flex">
        <div class="flex flex-1 flex-col md:flex-row">

            <div class="flex-1">

                {{-- Week navigatie --}}
                <div class="flex flex-col sm:flex-row items-center justify-center bg-eisgeel py-4 border-b gap-2">
                    <a href="?week={{ $prev->isoWeek() }}&year={{ $prev->year }}@if(request('user'))&user={{ request('user') }}@endif" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&lt;</a>
                    <span class="mx-6 text-xl font-semibold text-gray-700">
                        Week {{ $startOfWeek->format('W') }}
                        <span class="ml-2 text-sm text-gray-500">
                            ({{ $startOfWeek->format('d M') }} - {{ $startOfWeek->copy()->addDays(5)->format('d M') }})
                        </span>
                    </span>
                    <a href="?week={{ $next->isoWeek() }}&year={{ $next->year }}@if(request('user'))&user={{ request('user') }}@endif" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&gt;</a>
                    <a href="?week={{ now()->isoWeek() }}&year={{ now()->year }}@if(request('user'))&user={{ request('user') }}@endif" class="ml-4 px-4 py-1 rounded bg-eisgeel text-eisblue font-semibold shadow hover:bg-yellow-300 transition">
                        Spring naar deze week
                    </a>
                </div>

                {{-- Dagen + tijdblokken --}}
                <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x">
                @foreach ($days as $day)
                    <div class="flex-1">

                        {{-- Day header --}}
                        <div class="bg-eisgeel font-semibold py-2 border-b flex flex-col items-center justify-center">
                            <span class="block text-base">{{ ucfirst($day->locale('nl')->isoFormat('dddd')) }}</span>
                            <span class="block text-xs text-gray-500">{{ $day->format('d-m-Y') }}</span>
                        </div>

                        {{-- Time blocks --}}
                        <div>
                            @foreach ($timeBlocks as $time)
                                @php
                                    $startHour = (int) explode(':', $time)[0];
                                    $endHour = $startHour + 1;
                                    $endTime = sprintf('%02d:00', $endHour);
                                    $blockLabel = ltrim($time, '0') . '-' . ltrim($endTime, '0');

                                    $datetimeCheck = Carbon\Carbon::createFromFormat('Y-m-d H:i', $day->format('Y-m-d') . ' ' . $time)
                                        ->format('d/m/Y H:i:s');

                                    $lesData = $lessen[$datetimeCheck] ?? null;
                                    $heeftLes = $lesData && $lesData->leerling_id !== null && $lesData->auto !== null;
                                    $isAssigned = $lesData && !$heeftLes;

                                    $classes = 'time-block w-full text-center py-3 border-b transition cursor-pointer';
                                    if ($heeftLes) $classes .= ' bg-green-300 hover:bg-green-400';
                                    elseif ($isAssigned) $classes .= ' bg-yellow-200 hover:bg-yellow-300';
                                    else $classes .= ' hover:bg-gray-100';
                                @endphp

                                <button type="button"
                                    class="{{ $classes }}"
                                    data-date="{{ $day->format('Y-m-d') }}"
                                    data-time="{{ $time }}"
                                    data-user-id="{{ $selectedUserId }}">
                                    {{ $blockLabel }}
                                </button>
                            @endforeach
                        </div>

                    </div>
                @endforeach

                </div>

                {{-- Modal --}}
                <div id="agenda-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 min-w-[400px] relative">
                        <button id="agenda-modal-close" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl">&times;</button>
                        
                        <div id="modal-content">
                            <!-- Content will be loaded here via AJAX -->
                            <div class="text-center py-4">
                                <span class="text-gray-500">Laden...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>