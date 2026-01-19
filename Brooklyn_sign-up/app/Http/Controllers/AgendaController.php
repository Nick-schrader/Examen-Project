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

        // 1. Les ophalen via date + time (agenda-modal)
        if ($request->filled(['date', 'time'])) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
                ->format('d/m/Y H:i:s');

            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->join('auto', 'rooster_items.auto', '=', 'auto.id')
                ->select('rooster_items.*', 'leerling.naam as leerling_naam', 'auto.merk as auto_merk', 'auto.kenteken', 'telefoon', 'adres')
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.datum_en_tijd', $datetime)
                ->first();
        }

        // 2. Les ophalen via les_id (verslag-modal)
        if ($request->filled('les_id')) {
            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->join('auto', 'rooster_items.auto', '=', 'auto.id')
                ->select('rooster_items.*', 'leerling.naam as leerling_naam', 'auto.merk as auto_merk', 'auto.kenteken', 'telefoon', 'adres')
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.id', $request->les_id)
                ->first();
        }

        // Datum & tijd splitsen
        if ($les) {
            $carbon = Carbon::createFromFormat('d/m/Y H:i:s', $les->datum_en_tijd);
            $les->datum = $carbon->format('d-m-Y');
            $les->tijd  = $carbon->format('H:i');
        }

        $verslag = null;

        if ($les) {
            $verslag = DB::table('verslagen')
                ->where('rooster_item_id', $les->id)
                ->first();
        }

        return view('agenda', [
            'les' => $les,
            'verslag' => $verslag,
            'lessen' => $lessen,
            'startOfWeek' => $startOfWeek,
            'prev' => $prev,
            'next' => $next,
            'days' => $days,
            'timeBlocks' => $timeBlocks,
        ]);
    }

    public function verslagOpslaan(Request $request)
    {
        $request->validate([
            'rooster_item_id' => 'required|integer',
            'verslag' => 'required|string|min:5',
        ]);

        $bestaat = DB::table('verslagen')
            ->where('rooster_item_id', $request->rooster_item_id)
            ->exists();

        if ($bestaat) {
            DB::table('verslagen')
                ->where('rooster_item_id', $request->rooster_item_id)
                ->update([
                    'verslag' => $request->verslag,
                    'datum_aangepast' => now()->toDateString(),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('verslagen')->insert([
                'rooster_item_id' => $request->rooster_item_id,
                'verslag' => $request->verslag,
                'datum_gemaakt' => now()->toDateString(),
                'datum_aangepast' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Verslag opgeslagen.');
    }

    public function verslagVerwijderen(Request $request)
    {
        $request->validate([
            'rooster_item_id' => 'required|integer',
        ]);
    
        DB::table('verslagen')
            ->where('rooster_item_id', $request->rooster_item_id)
            ->delete();
    
        return redirect()->back()->with('success', 'Verslag verwijderd.');
    }

}
