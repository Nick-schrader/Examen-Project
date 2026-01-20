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
        // Determine which user we're viewing (instructor for admin, self for instructor)
        $selectedUserId = auth()->user()->type == 3 
            ? $request->input('user', auth()->id()) 
            : auth()->id();

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

        // Lessen ophalen voor deze week - FIXED VERSION
        $weekStart = $startOfWeek->copy()->startOfDay()->format('d/m/Y H:i:s');
        $weekEnd   = $startOfWeek->copy()->addDays(6)->endOfDay()->format('d/m/Y H:i:s');

        // Get all lessons for this instructor in this week
        $lessenCollection = RoosterItem::where('instructeur_id', $selectedUserId)
            ->get()
            ->filter(function($lesson) use ($weekStart, $weekEnd) {
                // Parse the stored date
                try {
                    $lessonDate = Carbon::createFromFormat('d/m/Y H:i:s', $lesson->datum_en_tijd);
                    $startDate = Carbon::createFromFormat('d/m/Y H:i:s', $weekStart);
                    $endDate = Carbon::createFromFormat('d/m/Y H:i:s', $weekEnd);
                    
                    return $lessonDate->between($startDate, $endDate);
                } catch (\Exception $e) {
                    return false;
                }
            })
            ->keyBy('datum_en_tijd');

        return view('agenda', [
            'lessen' => $lessenCollection,
            'startOfWeek' => $startOfWeek,
            'prev' => $prev,
            'next' => $next,
            'days' => $days,
            'timeBlocks' => $timeBlocks,
            'selectedUserId' => $selectedUserId,
        ]);
    }

    public function getLessonData(Request $request)
    {
        if (!$request->filled(['date', 'time'])) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $selectedUserId = auth()->user()->type == 3 
            ? $request->input('user_id', auth()->id()) 
            : auth()->id();

        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time)
            ->format('d/m/Y H:i:s');

        $les = RoosterItem::where('instructeur_id', $selectedUserId)
            ->where('datum_en_tijd', $datetime)
            ->first();

        if ($les) {
            if ($les->auto !== null) {
                // Already assigned
                return response()->json([
                    'success' => true,
                    'hasLesson' => true, // time block is assigned
                    'les' => $les
                ]);
            } else {
                // Time block exists but no car yet (rare now)
                return response()->json([
                    'success' => true,
                    'hasLesson' => false,
                    'isAssigned' => true,
                    'date' => $request->date,
                    'time' => $request->time
                ]);
            }
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
            ->where('datum_en_tijd', $datetime)
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

        $existing = RoosterItem::where('instructeur_id', $validated['instructeur_id'])
            ->where('datum_en_tijd', $datetime)
            ->first();

        if ($existing) {
            // Update car only
            $existing->update(['auto' => $validated['auto']]);
        } else {
            // Create new time block
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
            ->delete(); // delete regardless of car/student

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
}