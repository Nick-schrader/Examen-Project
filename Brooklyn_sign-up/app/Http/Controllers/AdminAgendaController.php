<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class AdminAgendaController extends Controller
{

    /**
     * Show the admin agenda view for a given week and instructor.
     */
    public function index(Request $request)
    {
        if ($request->user()->type !== 2 && $request->user()->type !== 3) {
            abort(403);
        }

        $currentWeek = $request->query('week', Carbon::now()->isoWeek());
        $currentYear = $request->query('year', Carbon::now()->year);
        $startOfWeek = Carbon::now()->setISODate($currentYear, $currentWeek)->startOfWeek(Carbon::MONDAY);
        $days = [];
        for ($i = 0; $i < 6; $i++) { // Monday to Saturday
            $days[] = $startOfWeek->copy()->addDays($i);
        }
        $timeBlocks = [];
        for ($h = 7; $h <= 19; $h++) {
            $timeBlocks[] = sprintf('%02d:00', $h);
        }
        $prev = $startOfWeek->copy()->subWeek();
        $next = $startOfWeek->copy()->addWeek();

        $instructors = User::where('type', 2)->get();

        return view('admin-views.agenda', [
            'days' => $days,
            'timeBlocks' => $timeBlocks,
            'startOfWeek' => $startOfWeek,
            'prev' => $prev,
            'next' => $next,
            'instructors' => $instructors,
        ]);
    }
}
