<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auto;

class AutoController extends Controller
{

    // Main path for car images
    public $carImagesFilePath = 'assets/cars';
    
    public function index()
    {
        $autos = Auto::all();
        return view('wagenpark', compact('autos'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'kenteken' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'beschikbaar' => 'required|in:1,2,3,4',
            'foto' => 'nullable|string|max:255',
        ]);

        $auto = Auto::findOrFail($id);
        $auto->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Auto succesvol bijgewerkt',
            'auto' => $auto
        ]);
    }

    public function getCarImages()
    {
        $carImagesPath = public_path($this->carImagesFilePath);
        $images = [];
        
        // Check if directory exists
        if (!is_dir($carImagesPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Directory does not exist: ' . $carImagesPath,
                'images' => []
            ]);
        }
        
        // Get the files under the car iameges path
        $files = scandir($carImagesPath);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                $images[] = $file;
            }
        }
        
        return response()->json([
            'success' => true,
            'images' => $images,
            'path' => $carImagesPath,
            'public_url' => asset('assets/cars/') 
        ]);
    }

    public function uploadCarImage(Request $request)
    {
        // Image is required
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the file and move it to the correct folder
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($this->carImagesFilePath), $filename);

        return response()->json([
            'success' => true,
            'filename' => $filename,
            'message' => 'Afbeelding succesvol geüpload'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'kenteken' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'beschikbaar' => 'required|in:1,2,3,4',
            'foto' => 'nullable|string|max:255',
        ]);

        // If image is not provided, use default image
        if (empty($validated['foto'])) {
            $validated['foto'] = 'default-car.jpg';
        }

        $auto = Auto::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Auto succesvol toegevoegd',
            'auto' => $auto
        ]);
    }

    public function remove($id) {
        $auto = Auto::findOrFail($id);

        if (!$auto) {
            return response()->json([
                'success' => false,
                'message' => 'Auto niet gevonden'
            ], 404);
        }

        $auto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Auto succesvol verwijderd',
            'auto' => $auto
        ]);
    }
}