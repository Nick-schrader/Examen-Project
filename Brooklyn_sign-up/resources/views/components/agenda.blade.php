<!-- Agenda Table -->
<div class="flex justify-center w-full mt-6">
<div class="w-full max-w-6xl overflow-hidden shadow-2xl bg-eisgeel rounded-2xl">
    <div class="flex">
        <!-- Main Content and Right Bar Wrapper -->
        <div class="flex flex-col flex-1 md:flex-row">
            <!-- Main Content -->
            <div class="flex-1">
                            <!-- Week Navigation -->
                            <div class="flex flex-col items-center justify-center gap-2 py-4 border-b sm:flex-row bg-eisgeel">
                                <a href="?week={{ $prev->isoWeek() }}&year={{ $prev->year }}" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&lt;</a>
                                <span class="mx-6 text-xl font-semibold text-gray-700">
                                    Week {{ $startOfWeek->format('W') }}<span class="ml-2 text-sm text-gray-500">({{ $startOfWeek->format('d M') }} - {{ $startOfWeek->copy()->addDays(5)->format('d M') }})</span>
                                </span>
                                <a href="?week={{ $next->isoWeek() }}&year={{ $next->year }}" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&gt;</a>
                                <a href="?week={{ \Carbon\Carbon::now()->isoWeek() }}&year={{ \Carbon\Carbon::now()->year }}" class="px-4 py-1 ml-4 font-semibold transition rounded shadow bg-eisgeel text-eisblue hover:bg-yellow-300">Spring naar deze week</a>
                            </div>
                            <!-- Days -->
                            <div class="flex flex-col divide-y md:flex-row md:divide-y-0 md:divide-x">
                                @foreach ($days as $i => $day)
                                    <div class="flex-1">
                                        <!-- Collapsible header for small screens -->
                                        <button class="flex items-center justify-center w-full gap-2 py-2 font-semibold border-b md:hidden bg-eisgeel day-toggle" data-day-index="{{ $i }}">
                                            <span class="block text-base">{{ ucfirst($day->locale('nl')->isoFormat('dddd')) }}</span>
                                            <span class="block text-xs text-gray-500">{{ $day->format('d-m-Y') }}</span>
                                            <span class="ml-2 toggle-icon">&#9660;</span>
                                        </button>
                                        <!-- Normal header for md+ screens -->
                                        <div class="hidden py-2 font-semibold border-b bg-eisgeel md:flex md:flex-col md:items-center md:justify-center">
                                            <span class="block text-base">{{ ucfirst($day->locale('nl')->isoFormat('dddd')) }}</span>
                                            <span class="block text-xs text-gray-500">{{ $day->format('d-m-Y') }}</span>
                                        </div>
                                        <div class="day-content" data-day-index="{{ $i }}">
                                            @foreach ($timeBlocks as $index => $time)
                                                @php
                                                    $startHour = (int) explode(':', $time)[0];
                                                    $endHour = $startHour + 1;
                                                    $endTime = sprintf('%02d:00', $endHour);
                                                    $blockLabel = ltrim($time, '0') . '-' . ltrim($endTime, '0');
                                                @endphp
                                                <div class="py-3 text-center transition border-b cursor-pointer hover:bg-gray-100 timeblock"
                                                     data-time="{{ $blockLabel }}" data-day="{{ $day->format('Y-m-d') }}">
                                                    {{ $blockLabel }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        <!-- Modal -->
                        <div id="agenda-modal" class="fixed inset-0 z-[50] flex items-center justify-center bg-black bg-opacity-40 hidden">
                            <div class="bg-white rounded-lg shadow-lg p-6 min-w-[300px] relative">
                                <button id="agenda-modal-close" class="absolute text-2xl text-gray-500 top-2 right-2 hover:text-red-500">&times;</button>
                                <!-- Modal content will go here -->
                            </div>
                        </div>
                        </div>
                    </div>
