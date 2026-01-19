<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strippenkaart;
use App\Models\User;

class StrippenkaartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $strippenkaart = Strippenkaart::firstOrCreate(
            ['leerling_id' => $user->id],
            [
                'tegoed' => 0,
                'verval_datum' => now()->addYear()
            ]
        );

        $strippenkaart->tegoed += $request->amount;
        $strippenkaart->save();

        return redirect()->route('agenda', [
            'modal' => 'strippenkaart',
            'user' => $user->id,
        ])->with('success', $request->amount . ' strippen toegevoegd aan ' . $user->naam . '!');

    }
}