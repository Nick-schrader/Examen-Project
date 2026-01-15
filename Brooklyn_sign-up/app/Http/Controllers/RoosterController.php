<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RoosterItem;
class RoosterController extends Controller
{
    private function getRooster($userId): object|array {
        return RoosterItem::with(['instructeur', 'leerling', 'autoItem', 'verslag'])->where('leerling_id', $userId)->get();
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
        echo '<script>console.log('.json_encode($rooster).')</script>';
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
    public function store(Request $request) {
        $date = $request->date;
        $time = $request->time;

        if (empty($date) || empty($time)) {
            return;
        }

        $datetime = $date . ' ' . $time;

        RoosterItem::create([
            'leerling_id' => $request->user()->id,
            'instructeur_id' => $request->instructeur,
            'datum_en_tijd' => $datetime,
            'auto' => $request->auto
        ]);

        return redirect('/rooster');
    }

    public function patch(Request $request) {
        $date = $request->date;
        $time = $request->time;

        if (empty($date) || empty($time)) {
            return;
        }

        $datetime = $date . ' ' . $time;

        $roosterItem = RoosterItem::find($request->id);
        if ($roosterItem) {
            $roosterItem->datum_en_tijd = $datetime;
            $roosterItem->auto = $request->auto;
            $roosterItem->instructeur_id = $request->instructeur;
            $roosterItem->save();
        }

        return redirect('/rooster');
    }

    public function destroy(Request $request) {

    }
}
