<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RoosterItem;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $selectedUserId = auth()->user()->type == 3
            ? $request->input('user', auth()->id())
            : auth()->id();

        $week = $request->input('week', now()->isoWeek());
        $year = $request->input('year', now()->year);

        $startOfWeek = now()->setISODate($year, $week)->startOfWeek();
        $prev = $startOfWeek->copy()->subWeek();
        $next = $startOfWeek->copy()->addWeek();

        $days = collect(range(0, 5))->map(fn ($i) => $startOfWeek->copy()->addDays($i));

        $timeBlocks = [];
        for ($i = 8; $i <= 16; $i++) {
            $timeBlocks[] = sprintf('%02d:00', $i);
        }

        // ---- LESDATA (single source of truth) ----
        $weekStart = $startOfWeek->copy()->startOfDay();
        $weekEnd = $startOfWeek->copy()->addDays(6)->endOfDay();

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

        // ---- MODAL LES ----
        $les = null;

        if ($request->filled(['date', 'time'])) {
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
                ->format('d/m/Y H:i:s');

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
        }

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

        if ($les) {
            $carbon = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $les->datum_en_tijd);
            $les->datum = $carbon->format('d-m-Y');
            $les->tijd = $carbon->format('H:i');
        }

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
            'targetUserId' => $targetUserId,
            'selectedUserId' => $selectedUserId,
        ]);


        // return view('agenda', compact(
        //     'lessen',
        //     'les',
        //     'verslag',
        //     'startOfWeek',
        //     'prev',
        //     'next',
        //     'days',
        //     'timeBlocks',
        //     'selectedUserId'
        // ));
    }

    public function getLessonData(Request $request)
    {
        if (!$request->filled(['date', 'time'])) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $selectedUserId = auth()->user()->type == 3
            ? $request->input('user_id')
            : auth()->id();

        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
            ->format('d/m/Y H:i:s');

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

    public function addLesson(Request $request)
    {
        // Only admins can add lessons
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'instructeur_id' => 'required|exists:users,id',
            'leerling_id' => 'required|exists:users,id',
            'auto' => 'required|exists:auto,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
        ]);

        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
            ->format('d/m/Y H:i:s');

        // Check if lesson already exists
        $existing = DB::table('rooster_items')
            ->where('instructeur_id', $request->instructeur_id)
            ->whereRaw("STR_TO_DATE(datum_en_tijd, '%d/%m/%Y %H:%i:%s') = ?", [$datetime])
            ->exists();

        if ($existing) {
            return response()->json(['error' => 'Er bestaat al een les op dit tijdstip'], 400);
        }

        DB::table('rooster_items')->insert([
            'instructeur_id' => $request->instructeur_id,
            'leerling_id' => $request->leerling_id,
            'auto' => $request->auto,
            'datum_en_tijd' => $datetime,
        ]);

        return response()->json(['success' => true, 'message' => 'Les succesvol toegevoegd']);
    }

    public function getStudents()
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $students = DB::table('users')
            ->where('type', 1)
            ->select('id', 'naam')
            ->get();

        return response()->json(['students' => $students]);
    }

    public function getCars()
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cars = DB::table('auto')
            ->select('id', 'merk', 'kenteken')
            ->get();

        return response()->json(['cars' => $cars]);
    }

    public function assignTimeBlock(Request $request)
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'instructeur_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'auto' => 'required|exists:auto,id',
        ]);

        $datetime = Carbon::createFromFormat('Y-m-d H:i', $validated['date'].' '.$validated['time'])
            ->format('d/m/Y H:i:s');

        // Check if already exists
        $existing = DB::table('rooster_items')
            ->where('instructeur_id', $validated['instructeur_id'])
            ->where('datum_en_tijd', $datetime)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Er bestaat al een tijdblok op dit moment'], 400);
        }

        // Create new time block WITHOUT leerling_id
        DB::table('rooster_items')->insert([
            'instructeur_id' => $validated['instructeur_id'],
            'auto' => $validated['auto'],
            'datum_en_tijd' => $datetime,
            'leerling_id' => null, // No student assigned yet
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tijdblok succesvol toegewezen'
        ]);
    }

    public function updateTimeBlock(Request $request)
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'rooster_item_id' => 'required|exists:rooster_items,id',
            'auto' => 'required|exists:auto,id',
        ]);

        DB::table('rooster_items')
            ->where('id', $validated['rooster_item_id'])
            ->update(['auto' => $validated['auto']]);

        return response()->json([
            'success' => true,
            'message' => 'Tijdblok succesvol bijgewerkt'
        ]);
    }

    public function deleteTimeBlock(Request $request)
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'instructeur_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
        ]);

        $datetime = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time'])
            ->format('d/m/Y H:i:s');

        $deleted = DB::table('rooster_items')
            ->where('instructeur_id', $validated['instructeur_id'])
            ->where('datum_en_tijd', $datetime)
            ->delete();

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

    public function getInstructors()
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $instructors = DB::table('users')
            ->where('type', 2)
            ->select('id', 'naam')
            ->get();

        return response()->json(['instructors' => $instructors]);
    }

    public function updateLesson(Request $request)
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rooster_item_id' => 'required|exists:rooster_items,id',
            'leerling_id' => 'required|exists:users,id',
            'auto' => 'required|exists:auto,id',
        ]);

        DB::table('rooster_items')
            ->where('id', $request->rooster_item_id)
            ->update([
                'leerling_id' => $request->leerling_id,
                'auto' => $request->auto,
            ]);

        return response()->json(['success' => true, 'message' => 'Les succesvol bijgewerkt']);
    }

    public function deleteLesson(Request $request)
    {
        if (auth()->user()->type != 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rooster_item_id' => 'required|exists:rooster_items,id',
        ]);

        DB::table('rooster_items')
            ->where('id', $request->rooster_item_id)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Les succesvol verwijderd']);
    }
}
