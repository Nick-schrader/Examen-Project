<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RoosterItem;

class AgendaController extends Controller
{
    // Toont de agenda pagina
    public function index(Request $request)
    {
        // Bepaal welke gebruiker geselecteerd is
        $selectedUserId = auth()->user()->type == 3
            ? $request->input('user', auth()->id())
            : auth()->id();

        // Bepaal de week en jaar
        $week = $request->input('week', now()->isoWeek());
        $year = $request->input('year', now()->year);

        // Bepaal de start van de week
        $startOfWeek = now()->setISODate($year, $week)->startOfWeek();
        $prev = $startOfWeek->copy()->subWeek();
        $next = $startOfWeek->copy()->addWeek();

        // Maak een collectie van de dagen in de week
        $days = collect(range(0, 5))->map(fn ($i) => $startOfWeek->copy()->addDays($i));

        // Maak tijdsblokken van 08:00 tot 20:00
        $timeBlocks = [];
        for ($i = 8; $i <= 16; $i++) {
            $timeBlocks[] = sprintf('%02d:00', $i);
        }

        // Haal alle lessen op voor de geselecteerde gebruiker in de week
        $weekStart = $startOfWeek->copy()->startOfDay();
        $weekEnd = $startOfWeek->copy()->addDays(6)->endOfDay();

        // Filter lessen binnen de week
        $lessen = RoosterItem::where('instructeur_id', $selectedUserId)
            ->get()
            ->filter(function ($les) use ($weekStart, $weekEnd) {
                try {
                    $dt = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', trim($les->datum_en_tijd));
                    return $dt->between($weekStart, $weekEnd);
                } catch (\Exception $e) {
                    return false;
                }
            })
            ->values();

        // Bepaal de geselecteerde les op basis van datum/tijd of les_id
        $les = null;

        // Zoek les op basis van datum en tijd
        if ($request->filled(['date', 'time'])) {
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
                ->format('d/m/Y H:i:s');

            // Zoek de les
            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->leftJoin('auto', 'rooster_items.auto', '=', 'auto.id')
                ->select(
                    'rooster_items.*',
                    'leerling.naam as leerling_naam',
                    'leerling.telefoon',
                    'leerling.adres',
                    'auto.merk as auto_merk',
                    'auto.kenteken'
                )
                ->where('rooster_items.instructeur_id', $selectedUserId)
                ->where('rooster_items.datum_en_tijd', $datetime)
                ->first();
        }

        // Zoek les op basis van les_id
        if ($request->filled('les_id')) {
            $les = DB::table('rooster_items')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->leftJoin('auto', 'rooster_items.auto', '=', 'auto.id')
                ->select(
                    'rooster_items.*',
                    'leerling.naam as leerling_naam',
                    'leerling.telefoon',
                    'leerling.adres',
                    'auto.merk as auto_merk',
                    'auto.kenteken'
                )
                ->where('rooster_items.instructeur_id', $selectedUserId)
                ->where('rooster_items.id', $request->les_id)
                ->first();
        }

        // Format datum en tijd van de les
        if ($les) {
            $carbon = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $les->datum_en_tijd);
            $les->datum = $carbon->format('d-m-Y');
            $les->tijd = $carbon->format('H:i');
        }

        // Haal het verslag op voor de les
        $verslag = null;
        if ($les) {
            $verslag = DB::table('verslag')
                ->where('rooster_item_id', $les->id)
                ->first();
        }

        // Haal alle verslagen op voor de instructeur, eventueel gefilterd op leerling
        $targetUserId = $request->input('user');

        // Alle verslagen initialiseren
        $alleVerslagen = collect();

        // Als er een specifieke leerling is geselecteerd, filter dan daarop
        if ($targetUserId) {
            $alleVerslagen = DB::table('verslag')
                ->join('rooster_items', 'verslag.rooster_item_id', '=', 'rooster_items.id')
                ->join('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
                ->select('verslag.*', 'rooster_items.datum_en_tijd', 'leerling.naam as leerling_naam')
                ->where('rooster_items.instructeur_id', auth()->id())
                ->where('rooster_items.leerling_id', $targetUserId)
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
            'targetUserId' => $targetUserId,
            'selectedUserId' => $selectedUserId,
        ]);
    }

    // Haal lesgegevens op voor een specifieke datum en tijd
    public function getLessonData(Request $request)
    {
        // Controleer of de vereiste parameters aanwezig zijn
        if (!$request->filled(['date', 'time'])) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Bepaal de geselecteerde gebruiker
        $selectedUserId = auth()->user()->type == 3
            ? $request->input('user_id')
            : auth()->id();

        // Format de datum en tijd
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
            ->format('d/m/Y H:i:s');

        // Zoek de les in de database
        $les = DB::table('rooster_items')
            ->leftJoin('users as leerling', 'rooster_items.leerling_id', '=', 'leerling.id')
            ->leftJoin('auto', 'rooster_items.auto', '=', 'auto.id')
            ->select(
                'rooster_items.*',
                'leerling.naam as leerling_naam',
                'leerling.telefoon',
                'leerling.adres',
                'auto.merk as auto_merk',
                'auto.kenteken'
            )
            ->where('rooster_items.instructeur_id', $selectedUserId)
            ->where('rooster_items.datum_en_tijd', $datetime)
            ->first();

        // Return de lesgegevens als JSON
        if ($les) {
            return response()->json([
                'success' => true,
                'hasLesson' => true,
                'les' => $les
            ]);
        }

        return response()->json([
            'success' => true,
            'hasLesson' => false,
            'isAssigned' => false,
            'date' => $request->date,
            'time' => $request->time
        ]);

    }

    // Voeg een nieuwe les toe
    public function addLesson(Request $request)
    {
        // Controleer of de gebruiker een instructeur is
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Valideer de request data
        $request->validate([
            'instructeur_id' => 'required|exists:users,id',
            'leerling_id' => 'required|exists:users,id',
            'auto' => 'required|exists:auto,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
        ]);

        // Format de datum en tijd
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
            ->format('d/m/Y H:i:s');

        // Controleer of er al een les bestaat op dat tijdstip voor de instructeur
        $existing = DB::table('rooster_items')
            ->where('instructeur_id', $request->instructeur_id)
            ->whereRaw("STR_TO_DATE(datum_en_tijd, '%d/%m/%Y %H:%i:%s') = ?", [$datetime])
            ->exists();

        // Als er al een les is, return een foutmelding
        if ($existing) {
            return response()->json(['error' => 'Er bestaat al een les op dit tijdstip'], 400);
        }

        // Voeg de nieuwe les toe aan de database
        DB::table('rooster_items')->insert([
            'instructeur_id' => $request->instructeur_id,
            'leerling_id' => $request->leerling_id,
            'auto' => $request->auto,
            'datum_en_tijd' => $datetime,
        ]);

        return response()->json(['success' => true, 'message' => 'Les succesvol toegevoegd']);
    }

    // Haal alle studenten op
    public function getStudents()
    {
        // Controleer of de gebruiker een instructeur is
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Haal alle studenten op uit de database
        $students = DB::table('users')
            ->where('type', 1)
            ->select('id', 'naam')
            ->get();

        return response()->json(['students' => $students]);
    }

    // Haal alle auto's op
    public function getCars()
    {
        // Controleer of de gebruiker een instructeur is
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Haal alle auto's op uit de database
        $cars = DB::table('auto')
            ->select('id', 'merk', 'kenteken')
            ->get();

        return response()->json(['cars' => $cars]);
    }

    // Wijs een tijdblok toe aan een instructeur
    public function assignTimeBlock(Request $request)
    {
        // Controleer of de gebruiker een instructeur is
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Valideer de request data
        $validated = $request->validate([
            'instructeur_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'auto' => 'required|exists:auto,id',
        ]);

        // Format de datum en tijd
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $validated['date'].' '.$validated['time'])
            ->format('d/m/Y H:i:s');

        // Controleer of er al een tijdblok bestaat voor de instructeur op dat tijdstip
        $existing = RoosterItem::where('instructeur_id', $validated['instructeur_id'])
            ->whereRaw("STR_TO_DATE(datum_en_tijd, '%d/%m/%Y %H:%i:%s') = ?", [$datetime])
            ->first();

        // Als er al een tijdblok is, werk dan alleen de auto bij, anders maak een nieuw tijdblok aan
        if ($existing) {
            $existing->update(['auto' => $validated['auto']]);
        } else {
            RoosterItem::create([
                'instructeur_id' => $validated['instructeur_id'],
                'auto' => $validated['auto'],
                'datum_en_tijd' => $datetime
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tijdblok succesvol toegewezen'
        ]);
    }

    // Verwijder een tijdblok van een instructeur
    public function deleteTimeBlock(Request $request)
    {
        // Controleer of de gebruiker een instructeur is
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Valideer de request data
        $validated = $request->validate([
            'instructeur_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
        ]);

        // Format de datum en tijd
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time'])
            ->format('d/m/Y H:i:s');

        // Verwijder het tijdblok uit de database
        $deleted = DB::table('rooster_items')
            ->where('instructeur_id', $validated['instructeur_id'])
            ->whereRaw("STR_TO_DATE(datum_en_tijd, '%d/%m/%Y %H:%i:%s') = ?", [$datetime])
            ->delete();

        // Return resultaat
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Tijdblok succesvol verwijderd'
            ]);
        } else {
            return response()->json([
                'error' => 'Tijdblok niet gevonden'
            ], 404);
        }
    }

    // Sla een verslag op voor een les
    public function verslagOpslaan(Request $request)
    {
        // Valideer de request data
        $request->validate([
            'rooster_item_id' => 'required|integer',
            'verslag' => 'required|string|min:5',
        ]);

        // Controleer of er al een verslag bestaat voor de les
        $bestaat = DB::table('verslag')
            ->where('rooster_item_id', $request->rooster_item_id)
            ->exists();

        // Werk het bestaande verslag bij of maak een nieuw verslag aan
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

    // Verwijder een verslag voor een les
    public function verslagVerwijderen(Request $request)
    {
        // Valideer de request data
        $request->validate([
            'rooster_item_id' => 'required|integer',
        ]);

        // Verwijder het verslag uit de database
        DB::table('verslag')
            ->where('rooster_item_id', $request->rooster_item_id)
            ->delete();

        return redirect()->back()->with('success', 'Verslag verwijderd.');
    }
}