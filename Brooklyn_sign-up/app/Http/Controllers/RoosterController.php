<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RoosterController extends Controller
{
    private function getRooster($userId): Object|Array {
        return DB::table('rooster_items')
                    ->where('leerling_id', $userId)
                    ->get();
    }

    private function sortAndFilterRooster($rooster, $past = true) {
        $now = new \DateTime();
        $rooster = $rooster->filter(function ($item) use ($now, $past) {
                        $itemDate = \DateTime::createFromFormat('d/m/Y H:i:s', $item->datum_en_tijd);
                        return $past ? $itemDate && $itemDate >= $now : $itemDate && $itemDate < $now;
                    })
                    ->sortBy(function ($item) {
                        $date = \DateTime::createFromFormat('d/m/Y H:i:s', $item->datum_en_tijd);
                        return $date ? $date->getTimestamp() : 0;
                    });

        return $past ? $rooster : $rooster->reverse();
    }

    public function get(Request $request): View {
        if ($request->user()->type !== 1) {
            abort(403);
        }
        $rooster = $this->getRooster($request->user()->id);
        return view('rooster.index', [
            'rooster' => $this->sortAndFilterRooster($rooster),
            'history' => false
        ]);
    }

    public function history(Request $request): View {
        if ($request->user()->type !== 1) {
            abort(403);
        }
        $rooster = $this->getRooster($request->user()->id);
        return view('rooster.index', [
            'rooster' => $this->sortAndFilterRooster($rooster, false),
            'history' => true
        ]);
    }
}
