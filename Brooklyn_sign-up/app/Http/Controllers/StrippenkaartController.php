<?php

namespace App\Http\Controllers;

use App\Models\Strippenkaart;

class StrippenkaartController extends Controller {
    private static function allowed($user_id, $kaart_id) {
        $strippenkaart = Strippenkaart::find($kaart_id);
        if (!$strippenkaart) abort(404, 'Strippenkaart niet gevonden.');
        if (!$strippenkaart->leerling_id) abort(400, 'Strippenkaart heeft geen leerling_id.');
        if ($strippenkaart->leerling_id !== $user_id) abort(403, 'Geen toegang tot deze strippenkaart.');
        return true;
    }

    public static function getNext($user_id) {
        return Strippenkaart::where('leerling_id', $user_id)
            ->where('tegoed', '>', 0)
            ->where('verval_datum', '>=', now())
            ->orderBy('created_at', 'asc')
            ->first();
    }

    public static function add($user_id, $kaart_id, $amount = 1) {
        if (!$user_id || !$kaart_id) abort(400, 'User ID of kaart ID ontbreekt.');
        if ($kaart_id === false) $kaart_id = StrippenkaartController::getNext($user_id);

        StrippenkaartController::allowed($user_id, $kaart_id);

        $strippenkaart = Strippenkaart::find($kaart_id);
        $strippenkaart->tegoed = $strippenkaart->tegoed + $amount;
        $strippenkaart->save();

        return true;
    }

    public static function remove($user_id, $kaart_id, $amount = 1) {
        if (!$user_id || !$kaart_id) abort(400, 'User ID of kaart ID ontbreekt.');
        if ($kaart_id === false) $kaart_id = StrippenkaartController::getNext($user_id);

        StrippenkaartController::allowed($user_id, $kaart_id);

        $strippenkaart = Strippenkaart::find($kaart_id);
        $strippenkaart->tegoed = $strippenkaart->tegoed - $amount;
        $strippenkaart->save();

        return true;
    }
}
