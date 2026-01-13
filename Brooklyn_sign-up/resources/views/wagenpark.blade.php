<x-app-layout>
    <div class="bg-eisgeel m-4 rounded-lg shadow-lg flex flex-col items-center py-10 space-y-6">
        <h1 class="text-3xl font-bold mb-6">Autos</h1>

    <!-- Mobile: Vertical scrolling -->
    <div class="block md:hidden w-full overflow-y-auto px-4 max-h-[600px]">
        <div class="flex flex-col gap-6 pb-4">
            @foreach($autos as $auto)
                <div onclick="openModal({{ $auto->id }})" class="bg-white border rounded-lg shadow-lg overflow-hidden w-full hover:shadow-xl transition-shadow duration-200 cursor-pointer">
                    <img src="{{ asset('assets/cars/' . $auto->foto) }}" alt="{{ $auto->merk }}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">{{ $auto->merk }}</h2>
                        <p>Kenteken: {{ $auto->kenteken }}</p>
                        <p>Type: {{ $auto->type == 1 ? 'Automaat' : 'Handgeschakeld' }}</p>
                        <p>
                            Beschikbaarheid: 
                            @switch($auto->beschikbaar)
                                @case(1) Beschikbaar @break
                                @case(2) Bezet @break
                                @case(3) Onderhoud @break
                                @case(4) Defect @break
                            @endswitch
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Desktop: Horizontal scrolling -->
    <div class="hidden md:block w-full px-4">
        <div class="overflow-x-auto py-2">
            <div class="flex gap-6 pb-4" style="min-width: min-content;">
                @foreach($autos as $auto)
                    <div onclick="openModal({{ $auto->id }})" class="bg-white border rounded-lg shadow-lg overflow-hidden w-64 flex-shrink-0 hover:shadow-xl transition-shadow duration-200 cursor-pointer">
                        <img src="{{ asset('assets/cars/' . $auto->foto) }}" alt="{{ $auto->merk }}" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h2 class="text-xl font-semibold">{{ $auto->merk }}</h2>
                            <p>Kenteken: {{ $auto->kenteken }}</p>
                            <p>Type: {{ $auto->type == 1 ? 'Automaat' : 'Handgeschakeld' }}</p>
                            <p>
                                Beschikbaarheid: 
                                @switch($auto->beschikbaar)
                                    @case(1) Beschikbaar @break
                                    @case(2) Bezet @break
                                    @case(3) Onderhoud @break
                                    @case(4) Defect @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Add New Car Button - Desktop (aligned to right) -->
        <div class="flex justify-end px-4 mt-2">
            <button onclick="openAddCarModal()" class="bg-eisblue text-white px-6 py-3 rounded-lg transition-colors font-semibold shadow-md">
                + Nieuwe Auto Toevoegen
            </button>
        </div>
    </div>

    <!-- Add New Car Button - Mobile (centered) -->
    <div class="block md:hidden w-full px-4 mt-2">
        <button onclick="openAddCarModal()" class="w-full bg-eisblue text-white px-6 py-3 rounded-lg transition-colors font-semibold shadow-md">
            + Nieuwe Auto Toevoegen
        </button>
    </div>

        <h1 class="text-3xl font-bold mb-6">Totaaloverzicht inzicht</h1>
        
        <!-- Graph Section -->
        <div class="w-full max-w-7xl bg-white rounded-lg shadow-lg p-6">
            <!-- Time Period Selector -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Auto Gebruik</h2>
                <div class="flex gap-2">
                    <button onclick="switchPeriod('week')" id="btn-week" class="px-4 py-2 rounded-md bg-eisblue text-white font-medium transition-colors">
                        Week
                    </button>
                    <button onclick="switchPeriod('month')" id="btn-month" class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 font-medium transition-colors">
                        Maand
                    </button>
                    <button onclick="switchPeriod('year')" id="btn-year" class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 font-medium transition-colors">
                        Jaar
                    </button>
                </div>
            </div>
            
            <!-- Graph Container -->
            <div class="h-80 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                <p class="text-gray-400 text-lg">Geen data beschikbaar</p>
            </div>
        </div>
    </div>

    <!-- Click On Car Modal -->
    <div id="carModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold" id="modalTitle">Auto Details</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold">&times;</button>
            </div>
            
            <!-- Tab Navigation -->
            <div class="flex border-b bg-gray-50">
                <button onclick="switchTab('bewerk')" id="tab-bewerk" class="flex-1 px-6 py-3 text-center font-medium border-b-2 border-eisblue text-eisblue transition-colors">
                    Bewerk Auto
                </button>
                <button onclick="switchTab('inzicht')" id="tab-inzicht" class="flex-1 px-6 py-3 text-center font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-colors">
                    Inzicht Auto
                </button>
            </div>

            <div class="p-6">
                <!-- Bewerk Auto Tab Content -->
                <div id="content-bewerk" class="tab-content">
                    <form id="editCarForm" onsubmit="submitForm(event)">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="carId" name="car_id">
                        
                        <div class="space-y-4">
                            <div>
                                <label for="merk" class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                                <input type="text" id="merk" name="merk" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="kenteken" class="block text-sm font-medium text-gray-700 mb-1">Kenteken</label>
                                <input type="text" id="kenteken" name="kenteken" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1">Automaat</option>
                                    <option value="2">Handgeschakeld</option>
                                </select>
                            </div>

                            <div>
                                <label for="beschikbaar" class="block text-sm font-medium text-gray-700 mb-1">Beschikbaarheid</label>
                                <select id="beschikbaar" name="beschikbaar" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1">Beschikbaar</option>
                                    <option value="2">Bezet</option>
                                    <option value="3">Onderhoud</option>
                                    <option value="4">Defect</option>
                                </select>
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="flex-1 bg-eisblue text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    Opslaan
                                </button>
                                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                    Annuleren
                                </button>
                            </div>

                            <!-- Error Message -->
                            <div id="errorMessage" class="hidden mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Inzicht Auto Tab Content -->
                <div id="content-inzicht" class="tab-content hidden">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-2" id="carNameInzicht"></h3>
                        <p class="text-gray-600" id="carDetailsInzicht"></p>
                    </div>

                    <!-- Graph Section for Individual Car -->
                    <div class="w-full bg-white rounded-lg border border-gray-200 p-4">
                        <!-- Time Period Selector -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Auto Gebruik</h3>
                            <div class="flex gap-2">
                                <button onclick="switchCarPeriod('week')" id="car-btn-week" class="px-3 py-1.5 text-sm rounded-md bg-eisblue text-white font-medium transition-colors">
                                    Week
                                </button>
                                <button onclick="switchCarPeriod('month')" id="car-btn-month" class="px-3 py-1.5 text-sm rounded-md bg-gray-200 text-gray-700 font-medium transition-colors">
                                    Maand
                                </button>
                                <button onclick="switchCarPeriod('year')" id="car-btn-year" class="px-3 py-1.5 text-sm rounded-md bg-gray-200 text-gray-700 font-medium transition-colors">
                                    Jaar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Graph Container -->
                        <div class="h-64 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                            <p class="text-gray-400">Geen data beschikbaar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Car Modal -->
    <div id="addCarModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Nieuwe Auto Toevoegen</h2>
                <button onclick="closeAddCarModal()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold">&times;</button>
            </div>
            <div class="p-6">
                <form id="addCarForm" onsubmit="submitAddCarForm(event)">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="add_merk" class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                            <input type="text" id="add_merk" name="merk" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="add_kenteken" class="block text-sm font-medium text-gray-700 mb-1">Kenteken</label>
                            <input type="text" id="add_kenteken" name="kenteken" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="add_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="add_type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="1">Automaat</option>
                                <option value="2">Handgeschakeld</option>
                            </select>
                        </div>

                        <div>
                            <label for="add_beschikbaar" class="block text-sm font-medium text-gray-700 mb-1">Beschikbaarheid</label>
                            <select id="add_beschikbaar" name="beschikbaar" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="1">Beschikbaar</option>
                                <option value="2">Bezet</option>
                                <option value="3">Onderhoud</option>
                                <option value="4">Defect</option>
                            </select>
                        </div>

                        <div>
                            <label for="add_foto" class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                            <input type="file" id="add_foto" name="foto" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Optioneel - upload een foto van de auto</p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 bg-eisblue text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                Toevoegen
                            </button>
                            <button type="button" onclick="closeAddCarModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                Annuleren
                            </button>
                        </div>

                        <!-- Error Message -->
                        <div id="addErrorMessage" class="hidden mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Image Uploader Modal -->
        <div id="imageUploaderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Foto's</h2>
                <button onclick="closeImageUploaderModal()" class="text-gray-500 hover:text-gray-700 text-3xl font-bold">&times;</button>
            </div>
            <div class="p-6">
                
            </div>
        </div>
    </div>


    <script>
        // Wait for the page to fully load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize wagenpark with autos data
            if (typeof window.initializeWagenpark === 'function') {
                window.initializeWagenpark(@json($autos));
            } else {
                // Fallback: wait a bit and try again
                setTimeout(function() {
                    if (typeof window.initializeWagenpark === 'function') {
                        window.initializeWagenpark(@json($autos));
                    } else {
                        console.error('wagenpark.js not loaded');
                    }
                }, 100);
            }
        });
    </script>
</x-app-layout>