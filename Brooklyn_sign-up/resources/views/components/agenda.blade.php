@php
    use Carbon\Carbon;
    $currentWeek = request()->query('week', Carbon::now()->isoWeek());
    $currentYear = request()->query('year', Carbon::now()->year);
    $startOfWeek = Carbon::now()->setISODate($currentYear, $currentWeek)->startOfWeek(Carbon::MONDAY);
    $days = [];
    for ($i = 0; $i < 6; $i++) { // 6 days: Mon-Sat
        $days[] = $startOfWeek->copy()->addDays($i);
    }
    $timeBlocks = [];
    for ($h = 7; $h <= 19; $h++) {
        $timeBlocks[] = sprintf('%02d:00', $h);
    }
    // Calculate previous and next week/year
    $prev = $startOfWeek->copy()->subWeek();
    $next = $startOfWeek->copy()->addWeek();
@endphp

<!-- Agenda Table -->
    <div class="w-full max-w-6xl bg-eisgeel rounded-2xl overflow-hidden shadow-2xl">
        <div class="flex">
            <!-- Left Bar -->
            <div class="bg-eisblue w-16"></div>
            <!-- Main Content -->
            <div class="flex-1">
                <!-- Week Navigation -->
                <div class="flex items-center justify-center bg-eisgeel py-4 border-b gap-2">
                    <a href="?week={{ $prev->isoWeek() }}&year={{ $prev->year }}" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&lt;</a>
                    <span class="mx-6 text-xl font-semibold text-gray-700">
                        Week {{ $startOfWeek->format('W') }}<span class="ml-2 text-sm text-gray-500">({{ $startOfWeek->format('d M') }} - {{ $startOfWeek->copy()->addDays(5)->format('d M') }})</span>
                    </span>
                    <a href="?week={{ $next->isoWeek() }}&year={{ $next->year }}" class="px-4 py-1 text-2xl font-bold text-eisblue hover:text-eisgroen">&gt;</a>
                    <a href="?week={{ \Carbon\Carbon::now()->isoWeek() }}&year={{ \Carbon\Carbon::now()->year }}" class="ml-4 px-4 py-1 rounded bg-eisgeel text-eisblue font-semibold shadow hover:bg-yellow-300 transition">Spring naar deze week</a>
                </div>
                <!-- Days -->
                <div class="flex divide-x">
                    @foreach ($days as $day)
                        <div class="flex-1">
                            <div class="bg-eisgeel text-center font-semibold py-2 border-b">
                                {{ ucfirst($day->locale('nl')->isoFormat('dddd')) }}<br>
                                <span class="text-xs text-gray-500">{{ $day->format('d-m-Y') }}</span>
                            </div>
                            @foreach ($timeBlocks as $time)
                                <div class="text-center py-3 border-b hover:bg-gray-100 transition">
                                    {{ $time }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Right Bar -->
            <div class="bg-eisblue w-16"></div>
        </div>
    </div>