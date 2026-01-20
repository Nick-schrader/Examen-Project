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

        // ⭐ ALLE lessen ophalen (ongeacht week)
        $lessen = DB::table('rooster_items')
            ->where('instructeur_id', auth()->id())
            ->get();

        // ⭐ ALLE datums normaliseren naar d/m/Y H:i:s
        $lessen = $lessen->map(function ($les) {

            $raw = trim($les->datum_en_tijd);

            // Probeer met seconden
            try {
                $carbon = Carbon::createFromFormat('d/m/Y H:i:s', $raw);
            } catch (\Exception $e1) {

                // Probeer zonder seconden
                try {
                    $carbon = Carbon::createFromFormat('d/m/Y H:i', $raw);
                } catch (\Exception $e2) {
                    // Laat originele waarde staan als alles faalt
                    return $les;
                }
            }

            // Forceer exact formaat
            $les->datum_en_tijd = $carbon->format('d/m/Y H:i:s');
            return $les;
        });

        // Les ophalen
        $les = null;

        // 1. Via datum + tijd
        if ($request->filled(['date', 'time'])) {

            $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
                ->format('d/m/Y H:i:s');

            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->join('auto', 'rooster_items.auto', '=', 'auto.id')
                ->select(
                    'rooster_items.*',
                    'leerling.naam as leerling_naam',
                    'leerling.telefoon',
                    'leerling.adres',
                    'auto.merk as auto_merk',
                    'auto.kenteken'
                )
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.datum_en_tijd', $datetime)
                ->first();
        }

        // 2. Via les_id
        if ($request->filled('les_id')) {

            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->join('auto', 'rooster_items.auto', '=', 'auto.id')
                ->select(
                    'rooster_items.*',
                    'leerling.naam as leerling_naam',
                    'leerling.telefoon',
                    'leerling.adres',
                    'auto.merk as auto_merk',
                    'auto.kenteken'
                )
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.id', $request->les_id)
                ->first();
        }

        // Datum & tijd splitsen voor modal
        if ($les) {
            $carbon = Carbon::createFromFormat('d/m/Y H:i:s', $les->datum_en_tijd);
            $les->datum = $carbon->format('d-m-Y');
            $les->tijd  = $carbon->format('H:i');
        }

        // Verslag ophalen
        $verslag = null;

        if ($les) {
            $verslag = DB::table('verslag')
                ->where('rooster_item_id', $les->id)
                ->first();
        }

        $targetUserId = $request->input('user');

        $alleVerslagen = collect();

        if ($targetUserId) {
            $alleVerslagen = DB::table('verslag')
                ->join('rooster_items', 'verslag.rooster_item_id', '=', 'rooster_items.id')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->select('verslag.*', 'rooster_items.datum_en_tijd', 'leerling.naam as leerling_naam')
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.leerling_id', $targetUserId) // ⭐ filter op leerling
                ->orderBy('verslag.created_at', 'desc')
                ->get();
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
            'alleVerslagen' => $alleVerslagen,
        ]);

    }

    public function verslagOpslaan(Request $request)
    {
        $request->validate([
            'rooster_item_id' => 'required|integer',
            'verslag' => 'required|string|min:5',
        ]);

        $bestaat = DB::table('verslag')
            ->where('rooster_item_id', $request->rooster_item_id)
            ->exists();

        if ($bestaat) {
            DB::table('verslag')
                ->where('rooster_item_id', $request->rooster_item_id)
                ->update([
                    'verslag' => $request->verslag,
                    'datum_aangepast' => now()->format('d/m/Y'),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('verslag')->insert([
                'rooster_item_id' => $request->rooster_item_id,
                'verslag' => $request->verslag,
                'datum_gemaakt' => now()->format('d/m/Y'),
                'datum_aangepast' => now()->format('d/m/Y'),
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

        DB::table('verslag')
            ->where('rooster_item_id', $request->rooster_item_id)
            ->delete();

        return redirect()->back()->with('success', 'Verslag verwijderd.');
    }
}
