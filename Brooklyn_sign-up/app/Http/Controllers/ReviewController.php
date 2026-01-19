<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Valideer de input
        $request->validate([
            'rooster_item_id' => 'required|integer|exists:rooster_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Check of er al een review bestaat voor dit rooster_item_id
        $existing = DB::table('reviews')
                      ->where('rooster_item_id', $request->rooster_item_id)
                      ->first();

        if ($existing) {
            // Als er al een review is, stuur terug met een foutmelding
            return redirect()->back()->with('error', 'Je hebt al een review gemaakt voor deze les.');
        }

        // Anders, maak een nieuwe review aan
        DB::table('reviews')->insert([
            'rooster_item_id' => $request->rooster_item_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Je review is verstuurd!');
    }

    public function history()
    {
        $lessons = DB::table('rooster_items')
                     ->where('leerling_id', auth()->id())
                     ->get();

        return view('rooster.index', compact('lessons'));
    }
}
