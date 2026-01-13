<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auto;

class AutoController extends Controller
{
    public function index()
    {
        $autos = Auto::all(); // Changed to use Eloquent model
        return view('wagenpark', compact('autos'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'kenteken' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'beschikbaar' => 'required|in:1,2,3,4',
        ]);

        $auto = Auto::findOrFail($id);
        $auto->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Auto succesvol bijgewerkt',
            'auto' => $auto
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'kenteken' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'beschikbaar' => 'required|in:1,2,3,4',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if present
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/cars'), $filename);
            $validated['foto'] = $filename;
        } else {
            // Default image if none provided
            $validated['foto'] = 'default-car.jpg';
        }

        $auto = Auto::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Auto succesvol toegevoegd',
            'auto' => $auto
        ]);
    }
}