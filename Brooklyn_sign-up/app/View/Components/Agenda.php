<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Agenda extends Component
{
    public $days, $timeBlocks, $startOfWeek, $prev, $next;

    public function __construct()
    {
        $currentWeek = request()->query('week', Carbon::now()->isoWeek());
        $currentYear = request()->query('year', Carbon::now()->year);

        $startOfWeek = Carbon::now()
            ->setISODate($currentYear, $currentWeek)
            ->startOfWeek(Carbon::MONDAY);

        $days = [];
        for ($i = 0; $i < 6; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        $timeBlocks = [];
        for ($h = 7; $h <= 19; $h++) {
            $timeBlocks[] = sprintf('%02d:00', $h);
        }

        $this->startOfWeek = $startOfWeek;
        $this->days = $days;
        $this->timeBlocks = $timeBlocks;
        $this->prev = $startOfWeek->copy()->subWeek();
        $this->next = $startOfWeek->copy()->addWeek();
    }


    public function render()
    {
        return view('components.agenda');
    }
}
