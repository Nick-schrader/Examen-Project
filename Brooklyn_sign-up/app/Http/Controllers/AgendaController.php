<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        // Week bepalen
        $week = $request->input('week', Carbon::now()->isoWeek());
        $year = $request->input('year', Carbon::now()->year);

        // Start van de week
        $startOfWeek = Carbon::now()
            ->setISODate($year, $week)
            ->startOfWeek();

        $prev = $startOfWeek->copy()->subWeek();
        $next = $startOfWeek->copy()->addWeek();

        // Dagen van de week
        $days = collect(range(0, 5))->map(fn($i) => $startOfWeek->copy()->addDays($i));

        // Tijdblokken
        $timeBlocks = [];
        for ($i = 8; $i <= 16; $i++) {
            $timeBlocks[] = sprintf('%02d:00', $i);
        }

        // Lessen ophalen voor deze week
        $weekStart = $startOfWeek->copy()->startOfDay();
        $weekEnd   = $startOfWeek->copy()->addDays(6)->endOfDay();

        $lessen = DB::table('rooster_items')
            ->where('instructeur_id', auth()->id())
            ->whereBetween('datum_en_tijd', [
                $weekStart->format('d/m/Y H:i:s'),
                $weekEnd->format('d/m/Y H:i:s')
            ])
            ->get();

        // Specifieke les voor modal
        $les = null;
        if ($request->filled(['date', 'time'])) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
                ->format('d/m/Y H:i:s');

            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->join('autos', 'rooster_items.auto', '=', 'autos.id')
                ->select('rooster_items.*', 'leerling.naam as leerling_naam', 'autos.merk as autos_merk', 'autos.kenteken')
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.datum_en_tijd', $datetime)
                ->first();
        }

        return view('agenda', [
            'les' => $les,
            'lessen' => $lessen,
            'startOfWeek' => $startOfWeek,
            'prev' => $prev,
            'next' => $next,
            'days' => $days,
            'timeBlocks' => $timeBlocks,
        ]);
    }
}