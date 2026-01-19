<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RoosterItem;
use App\Http\Controllers\StrippenkaartController as SC;

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

    private function validate(Request $request, $patch = false) {
        $rules = array_filter([
            'date' => 'required|date_format:d/m/Y',
            'time' => 'required|date_format:H:i:s',
            'instructeur' => 'required|exists:users,id',
            'auto' => 'required|exists:auto,id',
            'id' => $patch ? [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $roosterItem = RoosterItem::find($value);
                    if (!$roosterItem || $roosterItem->leerling_id !== $request->user()->id) {
                        $fail('Je mag alleen je eigen roosteritem aanpassen.');
                    }
                }
            ] : null,
        ]);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            abort(400, 'Validation failed: ' . json_encode($validator->errors()->all()));
        }
        return true;
    }

    private function timeCheck($request) {
        $lesson = RoosterItem::find($request->id);
        if (!$lesson) {
            return false;
        }
        $date = \DateTime::createFromFormat('d/m/Y H:i:s', $lesson->datum_en_tijd);
        $timestamp = $date ? $date->getTimestamp() : 0;
        return (now()->getTimestamp() + 86400) <= $timestamp ? true : false;
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
        $isPatch = $request->has('id');
        $date = $request->date;
        $time = $request->time;
        $user_id = $request->user()->id;

        if ($isPatch) {
            // PATCH (update)
            try {
                $this->validate($request, true);
            } catch (\Exception $e) {
                abort(400, $e->getMessage());
            }
            if (empty($date) || empty($time)) {
                abort(400, 'Date or time is empty');
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
        } else {
            // POST (create)
            $kaart = SC::getNext($user_id);
            if (!$kaart || !$kaart->tegoed) {
                abort(400, 'Geen geldige strippenkaart of tegoed gevonden.');
            }
            try {
                $this->validate($request);
            } catch (\Exception $e) {
                abort(400, $e->getMessage());
            }
            if (empty($date) || empty($time)) {
                abort(400, 'Date or time is empty');
            }
            $datetime = $date . ' ' . $time;
            RoosterItem::create([
                'leerling_id' => $user_id,
                'instructeur_id' => $request->instructeur,
                'datum_en_tijd' => $datetime,
                'auto' => $request->auto
            ]);
            return SC::remove($user_id, $kaart->id) ? redirect('/rooster') : abort(500, 'Strippenkaart verwijderen mislukt.');
        }
    }

    public function destroy(Request $request) {
        $id = $request->id;
        $roosterItem = RoosterItem::find($id);

        if (!$roosterItem) {
            abort(404, 'Rooster item niet gevonden.');
        }
        if ($roosterItem->leerling_id !== $request->user()->id) {
            abort(403, 'Je hebt geen rechten om dit rooster item te verwijderen.');
        }
        if (!$this->timeCheck($request)) {
            abort(400, 'Tijd is niet geldig voor deze bewerking.');
        }

        $roosterItem->delete();
        $kaart = SC::getNext($request->user()->id);
        if ($kaart) SC::add2($request->user()->id, false);

        return redirect('/rooster');
    }
}
