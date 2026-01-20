<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Sla een nieuwe review op
     */
    public function store(Request $request)
    {
        $request->validate([
            'rooster_item_id' => 'required|integer|exists:rooster_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $badWords = config('badwords.words');
        $foundBadWords = $this->containsBadWords($request->comment, $badWords);

        $status = empty($foundBadWords) ? 'approved' : 'flagged';

        $reviewId = DB::table('reviews')->insertGetId([
            'rooster_item_id' => $request->rooster_item_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!empty($foundBadWords)) {
            DB::table('review_flag')->insert([
                'review_id' => $reviewId,
                'reason' => 'Bevat scheldwoorden: ' . implode(', ', $foundBadWords),
            ]);
        }

        return back()->with('success', 'Review is opgeslagen');
    }

    /**
     * Controleer of een tekst scheldwoorden bevat
     */
    private function containsBadWords(string $text, array $badWords): array
    {
        $found = [];
        foreach ($badWords as $word) {
            if (stripos($text, $word) !== false) {
                $found[] = $word;
            }
        }
        return $found;
    }

    /**
     * Haal alle goedgekeurde reviews op met de naam van de gebruiker
     */
    public function showReviews()
    {
        $reviews = DB::table('reviews')
            ->join('rooster_items', 'reviews.rooster_item_id', '=', 'rooster_items.id')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.status', 'approved')
            ->select('reviews.*', 'users.naam as reviewer_name')
            ->get();

        return view('review', ['reviews' => $reviews]);
    }

    /**
     * Haal alle reviews voor het beheer, gescheiden in goedgekeurd en geflagd
     */
    public function adminReviews()
    {
        $approvedReviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.status', 'approved')
            ->select('reviews.*', 'users.naam as reviewer_name')
            ->get();

        $flaggedReviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.status', 'flagged')
            ->select('reviews.*', 'users.naam as reviewer_name')
            ->get();

        return view('admin.reviews', compact('approvedReviews', 'flaggedReviews'));
    }


    public function beheer()
    {
        // Goedkeurde reviews ophalen
        $approvedReviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.status', 'approved')
            ->select('reviews.*', 'users.naam as reviewer_name')
            ->get();

        // Geflagde reviews ophalen
    $flaggedReviews = DB::table('reviews')
        ->join('users', 'reviews.user_id', '=', 'users.id')
        ->leftJoin('review_flag', 'reviews.id', '=', 'review_flag.review_id')
        ->where('reviews.status', 'flagged')
        ->select('reviews.*', 'users.naam as reviewer_name', 'review_flag.reason')
        ->get();

        // Stuur naar de beheer-view
        return view('Beheer', compact('approvedReviews', 'flaggedReviews'));
    }

public function approve(Request $request)
{
    $review = DB::table('reviews')->where('id', $request->review_id)->first();

    DB::table('reviews')
        ->where('id', $request->review_id)
        ->update([
            'status' => 'approved',
            'updated_at' => now()
        ]);

    DB::table('review_flag')
        ->where('review_id', $request->review_id)
        ->delete();

    DB::table('korting')->insert([
        'leerling_id' => $review->user_id,
        'percentage'  => 10,
        'reason'      => 'Goedgekeurde review',
    ]);

    return back()->with('success', 'Review goedgekeurd en korting toegevoegd.');
}

    public function reject(Request $request)
    {
        DB::table('reviews')->where('id', $request->review_id)->delete();
        DB::table('review_flag')->where('review_id', $request->review_id)->delete();

        return back()->with('success', 'Review afgekeurd en verwijderd.');
    }

    public function history()
    {
        $lessons = DB::table('rooster_items')
                     ->where('leerling_id', auth()->id())
                     ->get();

        return view('rooster.index', compact('lessons'));
    }

    public function getKorting($userId)
    {
        $kortingen = DB::table('korting')
            ->where('leerling_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($kortingen);
    }

    public function removeKorting($id)
    {
        $deleted = DB::table('korting')->where('id', $id)->delete();
    }


}
