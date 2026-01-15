<?php
public function agenda(Request $request)
{
    $date = $request->input('date');
    $time = $request->input('time');

    $les = null;

    if ($date && $time) {
        $datetime = $date . ' ' . $time;

        $les = \DB::table('rooster_items')
            ->where('instructeur_id', auth()->id())
            ->where('datum_en_tijd', $datetime)
            ->first();
    }

    return view('agenda', [
        'days' => $days,
        'timeBlocks' => $timeBlocks,
        'les' => $les,
    ]);
}
