<?php

namespace App\Http\Controllers;

use App\Models\Strippenkaart;
use App\Models\User;
use Illuminate\Http\Request;

class StrippenkaartController extends Controller {
    // Check of gebruiker toegang heeft tot strippenkaart
    private static function allowed($user_id, $kaart_id) {
        $strippenkaart = Strippenkaart::find($kaart_id);
        if (!$strippenkaart) abort(404, 'Strippenkaart niet gevonden.');
        if (!$strippenkaart->leerling_id) abort(400, 'Strippenkaart heeft geen leerling_id.');
        if ($strippenkaart->leerling_id !== $user_id) abort(403, 'Geen toegang tot deze strippenkaart.');
        return true;
    }

    // Geeft de eerstvolgende geldige strippenkaart terug
    public static function getNext($user_id) {
        return Strippenkaart::where('leerling_id', $user_id)
            ->where('tegoed', '>', 0)
            ->where('verval_datum', '<=', now())
            ->orderBy('created_at', 'asc')
            ->first() ?? ['status' => 'empty', 'message' => 'niks gevonden'];
    }

    // Geeft alle geldige strippenkaarten terug
    public static function getNextAll($user_id) {
        $result = Strippenkaart::where('leerling_id', $user_id)
            ->where('tegoed', '>', 0)
            ->where('verval_datum', '<=', now())
            ->orderBy('created_at', 'asc')
            ->get();
        return $result->isEmpty() ? ['status' => 'empty', 'message' => 'niks gevonden'] : $result;
    }

    // Voeg strippen toe aan strippenkaart
    public function add(Request $request)
    {
        // Validatie van de request data
        $request->validate([
            'amount' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        // Zoekt de gebruiker
        $user = User::findOrFail($request->user_id);

        // Haalt of maakt een strippenkaart aan
        $strippenkaart = Strippenkaart::firstOrCreate(
            ['leerling_id' => $user->id],
            [
                'tegoed' => 0,
                'verval_datum' => now()->addYear()
            ]
        );

        // Voegt het opgegeven aantal strippen toe
        $strippenkaart->tegoed += $request->amount;
        $strippenkaart->save();

        // Redirect met succesbericht
        return redirect()->route('agenda', [
            'modal' => 'strippenkaart',
            'user' => $user->id,
        ])->with('success', $request->amount . ' strippen toegevoegd aan ' . $user->naam . '!');

    }

    // Pas het tegoed van een strippenkaart aan
    public static function add2tegoed($user_id, $kaart_id, $amount = 1) {
        if (!$user_id || !$kaart_id) abort(400, 'User ID of kaart ID ontbreekt.');
        if ($kaart_id === false) $kaart_id = StrippenkaartController::getNext($user_id)->id;
        if (!$kaart_id) abort(400, 'kaart id niet correct opgehaald');

        // Controleer of de gebruiker toegang heeft tot de strippenkaart
        StrippenkaartController::allowed($user_id, $kaart_id);
        $strippenkaart = Strippenkaart::find($kaart_id);

        // Voeg het bedrag toe aan het tegoed
        if ($strippenkaart->tegoed + $amount <= 0) return false;

        // Werk het tegoed bij
        $strippenkaart->tegoed = $strippenkaart->tegoed + $amount;
        $strippenkaart->save();

        return true;
    }

    // Verwijder strippen van strippenkaart
    public static function removeFromTegoed($user_id, $kaart_id, $amount = 1) {
        if (!$user_id || !$kaart_id) abort(400, 'User ID of kaart ID ontbreekt.');
        if ($kaart_id === false) $kaart_id = StrippenkaartController::getNext($user_id)->id;
        if (!$kaart_id) abort(400, 'kaart id niet correct opgehaald');

        // Controleer of de gebruiker toegang heeft tot de strippenkaart
        StrippenkaartController::allowed($user_id, $kaart_id);
        $strippenkaart = Strippenkaart::find($kaart_id);

        // Verwijder het bedrag van het tegoed
        if ($strippenkaart->tegoed - $amount < 0) return false;

        // Werk het tegoed bij
        $strippenkaart->tegoed = $strippenkaart->tegoed - $amount;
        $strippenkaart->save();

        return true;
    }
}