@props([
    'date' => null,
    'time' => null,
    'userId' => null,
    'week' => null,
    'year' => null,
    'les' => null
])

<div class="pt-4 flex flex-col gap-3 text-eisblue">
    <div class="bg-eisgeel rounded-lg p-4 shadow-sm border border-eisgroen/30">
        <h2 class="text-lg font-semibold text-eisblue mb-3">
            {{ $les ? 'Tijdblok Bewerken' : 'Tijdblok Toewijzen' }}
        </h2>
        
        @if($date && $time)
            <div class="space-y-2">
                <p class="text-sm"><strong>Datum:</strong> {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</p>
                <p class="text-sm"><strong>Tijd:</strong> {{ $time }}</p>
            </div>
        @endif
    </div>

    @if(!$les)
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4 text-sm shadow-sm">
            Geen tijdblok toegewezen.
        </div>
    @else
        <div class="bg-eisgroen/20 text-eisblue border border-eisgroen rounded-lg p-4 text-sm shadow-sm">
            <p><strong>Huidige tijdblok:</strong></p>
            @if($les->leerling_naam)
                <p>Leerling: {{ $les->leerling_naam }}</p>
            @endif
            <p>Auto: {{ $les->auto_merk ?? 'Geen' }} {{ $les->kenteken ? '(' . $les->kenteken . ')' : '' }}</p>
        </div>
    @endif

    <form id="timeblock-form" class="space-y-4">
        @csrf
        
        @if($les)
            <input type="hidden" name="rooster_item_id" value="{{ $les->id }}">
        @endif
        
        <input type="hidden" name="instructeur_id" value="{{ $userId }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="time" value="{{ $time }}">

        <div>
            <label class="block text-sm font-medium mb-1">Auto <span class="text-red-500">*</span></label>
            <select name="auto" id="auto-select" required 
                    class="w-full rounded-lg border-gray-300 focus:border-eisblue focus:ring-eisblue">
                <option value="">Selecteer auto...</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-eisblue text-white py-2 rounded-lg hover:bg-eisgroen transition">
                {{ $les ? 'Tijdblok Bijwerken' : 'Tijdblok Toewijzen' }}
            </button>
            
            @if($les)
                <button type="button" id="delete-timeblock-btn" 
                        class="px-4 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
                    Verwijderen
                </button>
            @endif
        </div>
    </form>

    <div id="form-message" class="hidden rounded-lg p-3 text-sm"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('timeblock-form');
    const autoSelect = document.getElementById('auto-select');
    const messageDiv = document.getElementById('form-message');
    const deleteBtn = document.getElementById('delete-timeblock-btn');

    // Current lesson data
    const currentLesson = @json($les);

    // Load cars
    fetch('/api/cars')
        .then(res => res.json())
        .then(data => {
            data.cars.forEach(car => {
                const option = document.createElement('option');
                option.value = car.id;
                option.textContent = `${car.merk} (${car.kenteken})`;
                if (currentLesson && car.id == currentLesson.auto) {
                    option.selected = true;
                }
                autoSelect.appendChild(option);
            });
        })
        .catch(err => console.error('Error loading cars:', err));

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Choose endpoint based on whether we're updating or creating
        const url = currentLesson ? '/agenda/update-timeblock' : '/agenda/assign-timeblock';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'bg-green-50 text-green-700 border border-green-200 rounded-lg p-3 text-sm';
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('hidden');
                
                // Reload page after 1 second
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                messageDiv.className = 'bg-red-50 text-red-700 border border-red-200 rounded-lg p-3 text-sm';
                messageDiv.textContent = data.error || 'Er is een fout opgetreden';
                messageDiv.classList.remove('hidden');
            }
        })
        .catch(err => {
            messageDiv.className = 'bg-red-50 text-red-700 border border-red-200 rounded-lg p-3 text-sm';
            messageDiv.textContent = 'Er is een fout opgetreden bij het opslaan';
            messageDiv.classList.remove('hidden');
            console.error('Error:', err);
        });
    });

    // Handle delete
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (!confirm('Weet je zeker dat je dit tijdblok wilt verwijderen?')) {
                return;
            }

            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            fetch('/agenda/delete-timeblock', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    messageDiv.className = 'bg-green-50 text-green-700 border border-green-200 rounded-lg p-3 text-sm';
                    messageDiv.textContent = data.message;
                    messageDiv.classList.remove('hidden');
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    messageDiv.className = 'bg-red-50 text-red-700 border border-red-200 rounded-lg p-3 text-sm';
                    messageDiv.textContent = data.error || 'Er is een fout opgetreden';
                    messageDiv.classList.remove('hidden');
                }
            })
            .catch(err => {
                messageDiv.className = 'bg-red-50 text-red-700 border border-red-200 rounded-lg p-3 text-sm';
                messageDiv.textContent = 'Er is een fout opgetreden bij het verwijderen';
                messageDiv.classList.remove('hidden');
                console.error('Error:', err);
            });
        });
    }
});
</script>