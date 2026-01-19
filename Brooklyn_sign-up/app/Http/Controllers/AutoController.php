<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Auto;
use Carbon\Carbon;

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
        
        if (!is_dir($carImagesPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Directory does not exist: ' . $carImagesPath,
                'images' => []
            ]);
        }
        
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
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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

        $defaultCarImageFile = 'default-car.png';

        if (empty($validated['foto'])) {
            $validated['foto'] = $defaultCarImageFile;
        }

        $auto = Auto::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Auto succesvol toegevoegd',
            'auto' => $auto
        ]);
    }

    public function remove($id) 
    {
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

    public function getCarUsageData(Request $request, $id = null)
    {
        try {
            $period = $request->input('period', 'week');
            
            // Calculate date range based on period
            switch ($period) {
                case 'week':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'year':
                    $startDate = now()->startOfYear();
                    $endDate = now()->endOfYear();
                    break;
                default:
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
            }

            Log::info('Fetching usage data', [
                'period' => $period,
                'startDate' => $startDate->format('Y-m-d H:i:s'),
                'endDate' => $endDate->format('Y-m-d H:i:s'),
                'carId' => $id,
                'now' => now()->format('Y-m-d H:i:s')
            ]);

            // Fetch all usage records that overlap the period
            $query = DB::table('auto_gebruik')
                ->join('auto', 'auto_gebruik.auto_id', '=', 'auto.id')
                ->whereNotNull('auto_gebruik.eind_gebruik')
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_gebruik', [$startDate, $endDate])
                      ->orWhereBetween('eind_gebruik', [$startDate, $endDate])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('start_gebruik', '<', $startDate)
                             ->where('eind_gebruik', '>', $endDate);
                      });
                });

            if ($id) {
                $query->where('auto_gebruik.auto_id', $id);
            }

            $usageRecords = $query->select(
                'auto_gebruik.auto_id',
                'auto.merk',
                'auto_gebruik.start_gebruik',
                'auto_gebruik.eind_gebruik'
            )->get();

            Log::info('Raw records fetched', [
                'count' => $usageRecords->count(),
                'records' => $usageRecords->toArray()
            ]);

            // Process data
            $processedData = collect();

            foreach ($usageRecords as $record) {
                $start = Carbon::parse($record->start_gebruik);
                $end   = Carbon::parse($record->eind_gebruik);

                // Clamp to period boundaries
                $start = $start->copy()->max($startDate);
                $end   = $end->copy()->min($endDate);

                $date = $start->format('Y-m-d');
                $durationHours = $end->diffInMinutes($start) / 60;

                $existing = $processedData->first(function ($item) use ($date, $record) {
                    return $item->date === $date && $item->auto_id === $record->auto_id;
                });

                if ($existing) {
                    $existing->total_hours += $durationHours;
                } else {
                    $processedData->push((object)[
                        'date' => $date,
                        'auto_id' => $record->auto_id,
                        'merk' => $record->merk,
                        'total_hours' => $durationHours
                    ]);
                }
            }

            Log::info('Processed data', [
                'count' => $processedData->count(),
                'data' => $processedData->toArray()
            ]);

            $formattedData = $this->formatGraphData($processedData, $period, $startDate, $endDate, $id);

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'period' => $period,
                'debug' => [
                    'startDate' => $startDate->format('Y-m-d'),
                    'endDate' => $endDate->format('Y-m-d'),
                    'rawRecords' => $usageRecords->count(),
                    'processedRecords' => $processedData->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getCarUsageData: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                'data' => [
                    'labels' => [],
                    'datasets' => []
                ]
            ], 200);
        }
    }

    private function formatGraphData($data, $period, $startDate, $endDate, $carId = null)
    {
        try {
            $dates = [];
            $current = $startDate->copy()->startOfDay();
            $end = $endDate->copy()->startOfDay();

            if ($period === 'year') {
                while ($current->lte($end)) {
                    $dates[] = $current->format('Y-m-01');
                    $current->addMonth();
                }
            } else {
                while ($current->lte($end)) {
                    $dates[] = $current->format('Y-m-d');
                    $current->addDay();
                }
            }

            Log::info('Date range for graph', [
                'dates' => $dates,
                'dataCount' => $data->count(),
                'period' => $period
            ]);

            if ($carId) {
                $usage = [];
                foreach ($dates as $date) {
                    $dayData = ($period === 'year')
                        ? $data->filter(fn($item) => Carbon::parse($item->date)->format('Y-m') === Carbon::parse($date)->format('Y-m'))
                        : $data->firstWhere('date', $date);

                    $hours = $period === 'year' ? $dayData->sum('total_hours') : ($dayData ? $dayData->total_hours : 0);
                    // *-1 because the hours are negatives for some reason
                    $usage[] = round($hours, 2) *-1;
                }

                return [
                    'labels' => array_map(fn($date) => $this->formatDateLabel($date, $period), $dates),
                    'datasets' => [[
                        'label' => 'Gebruikstijd (uren)',
                        'data' => $usage,
                        'borderColor' => 'rgb(34, 197, 94)',
                        'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                        'tension' => 0.4,
                        'fill' => true
                    ]]
                ];
            } else {
                $carGroups = $data->groupBy('auto_id');
                $datasets = [];
                $colors = [
                    ['border' => 'rgb(34, 197, 94)', 'bg' => 'rgba(34, 197, 94, 0.1)'],
                    ['border' => 'rgb(59, 130, 246)', 'bg' => 'rgba(59, 130, 246, 0.1)'],
                    ['border' => 'rgb(234, 179, 8)', 'bg' => 'rgba(234, 179, 8, 0.1)'],
                    ['border' => 'rgb(239, 68, 68)', 'bg' => 'rgba(239, 68, 68, 0.1)'],
                    ['border' => 'rgb(168, 85, 247)', 'bg' => 'rgba(168, 85, 247, 0.1)'],
                ];

                $colorIndex = 0;
                foreach ($carGroups as $autoId => $carData) {
                    $usage = [];
                    $carName = $carData->first()->merk;

                    foreach ($dates as $date) {
                        $dayData = ($period === 'year')
                            ? $carData->filter(fn($item) => Carbon::parse($item->date)->format('Y-m') === Carbon::parse($date)->format('Y-m'))
                            : $carData->firstWhere('date', $date);

                        $hours = $period === 'year' ? $dayData->sum('total_hours') : ($dayData ? $dayData->total_hours : 0);
                        // *-1 because the hours are negatives for some reason
                        $usage[] = round($hours, 2) *-1;
                    }

                    $color = $colors[$colorIndex % count($colors)];
                    $datasets[] = [
                        'label' => $carName,
                        'data' => $usage,
                        'borderColor' => $color['border'],
                        'backgroundColor' => $color['bg'],
                        'tension' => 0.4,
                        'fill' => true
                    ];

                    $colorIndex++;
                }

                return [
                    'labels' => array_map(fn($date) => $this->formatDateLabel($date, $period), $dates),
                    'datasets' => $datasets
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in formatGraphData: ' . $e->getMessage());
            return [
                'labels' => [],
                'datasets' => []
            ];
        }
    }

    private function formatDateLabel($date, $period)
    {
        $carbon = Carbon::parse($date);

        switch ($period) {
            case 'week':
                $days = ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'];
                return $days[$carbon->dayOfWeek] . ' ' . $carbon->day;
            case 'month':
                return $carbon->day . ' ' . $carbon->shortMonthName;
            case 'year':
                $months = ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
                return $months[$carbon->month - 1];
            default:
                return $carbon->format('j M');
        }
    }
}
