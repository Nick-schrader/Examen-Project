@props([
    'les' => null,
    'verslag' => null,
    'lessen' => collect(),
    'startOfWeek',
    'prev',
    'next',
    'days',
    'timeBlocks',
    'selectedUserId'
])

<div class="flex justify-center w-full mt-6">
<div class="w-full max-w-6xl bg-eisgeel rounded-2xl overflow-hidden shadow-2xl">
    <div class="flex">
        <div class="flex flex-1 flex-col md:flex-row">

            <div class="flex-1">

                {{-- Week navigatie --}}
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

                {{-- Dagen + tijdblokken --}}
                <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x">
                    @foreach ($days as $day)
                        <div class="flex-1">

                            {{-- Dag header --}}
                            <div class="bg-eisgeel font-semibold py-2 border-b flex flex-col items-center justify-center">
                                <span class="block text-base">{{ ucfirst($day->locale('nl')->isoFormat('dddd')) }}</span>
                                <span class="block text-xs text-gray-500">{{ $day->format('d-m-Y') }}</span>
                            </div>

                            {{-- Tijdblokken --}}
                            <div>
                                @foreach ($timeBlocks as $time)
                                    @php
                                        $startHour = (int) explode(':', $time)[0];
                                        $endTime = sprintf('%02d:00', $startHour + 1);
                                        $blockLabel = ltrim($time, '0') . '-' . ltrim($endTime, '0');

                                        $datetimeCheck = \Carbon\Carbon::createFromFormat(
                                            'Y-m-d H:i',
                                            $day->format('Y-m-d') . ' ' . $time
                                        )->format('d/m/Y H:i:s');

                                        $heeftLes = $lessen->contains('datum_en_tijd', $datetimeCheck);
                                    @endphp

                                    <button type="button"
                                        class="time-block w-full text-center py-3 border-b transition cursor-pointer
                                            {{ $heeftLes ? 'bg-eisgroen hover:bg-[#a3b97f]' : 'hover:bg-[#eae3b7]' }}"
                                        data-date="{{ $day->format('Y-m-d') }}"
                                        data-time="{{ $time }}"
                                        data-user-id="{{ $selectedUserId }}"
                                        data-week="{{ $startOfWeek->isoWeek() }}"
                                        data-year="{{ $startOfWeek->year }}">
                                        {{ $blockLabel }}
                                    </button>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Modal --}}
                <div id="agenda-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg min-w-[300px] w-full max-w-md overflow-hidden ml-[20px] mr-[20px]">
                        {{-- Header --}}
                        <div class="bg-eisblue text-white px-4 py-3 flex items-center justify-between">
                            <x-application-logo class="block w-auto text-gray-800 fill-current h-9" />
                            <button id="agenda-modal-close" class="text-white hover:text-red-500 text-3xl leading-none">&times;</button>
                        </div>
                        {{-- Content --}}
                        <div id="modal-content" class="p-6">
                            @if(auth()->user()->type == 3)
                                <x-addInstructorTimeblock 
                                    :date="request('date')" 
                                    :time="request('time')" 
                                    :userId="$selectedUserId"
                                    :week="$startOfWeek->isoWeek()"
                                    :year="$startOfWeek->year"
                                    :les="$les" />
                            @else
                                <x-leerlingDataOphalen :les="$les" :verslag="$verslag" />
                            @endif
                        </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

{{-- Verslag modal --}}
<div id="verslag-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg min-w-[300px] w-full max-w-xl overflow-hidden ml-[20px] mr-[20px]">

        {{-- Header --}}
        <div class="bg-eisblue text-white px-4 py-3 flex items-center justify-between">
            <x-application-logo class="block w-auto h-9 fill-current text-white" />
            <button id="verslag-modal-close" class="text-white hover:text-red-300 text-3xl leading-none">&times;</button>
        </div>

        {{-- Content --}}
        <div class="p-6">
            <x-verslagBijwerken :les="$les" :verslag="$verslag" />
        </div>

    </div>
</div>