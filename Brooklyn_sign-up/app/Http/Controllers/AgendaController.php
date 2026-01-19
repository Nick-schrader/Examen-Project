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

        // Start van de week (maandag)
        $startOfWeek = Carbon::now()
            ->setISODate($year, $week)
            ->startOfWeek();

        $prev = $startOfWeek->copy()->subWeek();
        $next = $startOfWeek->copy()->addWeek();

        // Maandag t/m zaterdag
        $days = collect(range(0, 5))
            ->map(fn ($i) => $startOfWeek->copy()->addDays($i));

        // Tijdblokken 08:00 – 16:00
        $timeBlocks = [];
        for ($i = 8; $i <= 16; $i++) {
            $timeBlocks[] = sprintf('%02d:00', $i);
        }

        /**
         * ⭐ ALLE lessen ophalen
         * + Carbon-object toevoegen
         */
        $lessen = DB::table('rooster_items')
            ->where('instructeur_id', auth()->id())
            ->get()
            ->map(function ($les) {

                $raw = trim($les->datum_en_tijd);

                try {
                    $les->carbon = Carbon::createFromFormat('d/m/Y H:i:s', $raw);
                } catch (\Exception $e1) {
                    try {
                        $les->carbon = Carbon::createFromFormat('d/m/Y H:i', $raw);
                    } catch (\Exception $e2) {
                        $les->carbon = null;
                    }
                }

                return $les;
            });

        // Les ophalen (voor modal)
        $les = null;

        // 1️⃣ Via datum + tijd
        if ($request->filled(['date', 'time'])) {

            $moment = Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->date . ' ' . $request->time
            );

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
                ->get()
                ->first(function ($item) use ($moment) {
                    try {
                        return Carbon::createFromFormat('d/m/Y H:i:s', $item->datum_en_tijd)
                            ->equalTo($moment);
                    } catch (\Exception $e) {
                        return false;
                    }
                });
        }

        // 2️⃣ Via les_id
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

        // Datum & tijd voor modal
        if ($les) {
            $carbon = Carbon::createFromFormat('d/m/Y H:i:s', $les->datum_en_tijd);
            $les->datum = $carbon->format('d-m-Y');
            $les->tijd  = $carbon->format('H:i');
        }

        // Verslag ophalen
        $verslag = null;
        if ($les) {
            $verslag = DB::table('verslagen')
                ->where('rooster_item_id', $les->id)
                ->first();
        }

        return view('agenda', compact(
            'les',
            'verslag',
            'lessen',
            'startOfWeek',
            'prev',
            'next',
            'days',
            'timeBlocks'
        ));
    }
}
