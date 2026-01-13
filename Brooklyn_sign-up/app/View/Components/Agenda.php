<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;
use App\Models\RoosterItem;

class Agenda extends Component
{
    public $days, $timeBlocks, $startOfWeek, $prev, $next;
    public $items;
    public $selectedUserId;

    public function __construct()
    {
        $currentWeek = request()->query('week', Carbon::now()->isoWeek());
        $currentYear = request()->query('year', Carbon::now()->year);

        $this->selectedUserId = request()->query('user');

        $startOfWeek = Carbon::now()
            ->setISODate($currentYear, $currentWeek)
            ->startOfWeek(Carbon::MONDAY);

        // Dagen
        $days = [];
        for ($i = 0; $i < 6; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        // Tijdblokken
        $timeBlocks = [];
        for ($h = 7; $h <= 19; $h++) {
            $timeBlocks[] = sprintf('%02d:00', $h);
        }

        // Agenda-items ophalen
        $items = collect();

        if ($this->selectedUserId) {
            $items = RoosterItem::where(function ($q) {
                    $q->where('leerling_id', $this->selectedUserId)
                      ->orWhere('instructeur_id', $this->selectedUserId);
                })
                ->whereBetween('datum_en_tijd', [
                    $startOfWeek->copy()->startOfDay(),
                    $startOfWeek->copy()->addDays(5)->endOfDay()
                ])
                ->get();
        }

        $this->startOfWeek = $startOfWeek;
        $this->days = $days;
        $this->timeBlocks = $timeBlocks;
        $this->prev = $startOfWeek->copy()->subWeek();
        $this->next = $startOfWeek->copy()->addWeek();
        $this->items = $items;
    }

    public function render()
    {
        return view('components.agenda');
    }
}
