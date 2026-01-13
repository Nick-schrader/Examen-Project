<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RoosterController extends Controller
{
    public function get(Request $request): View {
        $userId = $request->user()->id;
        $roosters = DB::table('rooster_items')
                    ->where('leerling_id', $userId)
                    ->get();

        return view('rooster.index ', [

        ]);
    }
}
