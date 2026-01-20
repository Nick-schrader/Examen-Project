document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('agenda-modal');
    const modalContent = document.getElementById('modal-content');
    const closeBtn = document.getElementById('agenda-modal-close');
    const userType = document.querySelector('meta[name="user-type"]').content;
    const isAdmin = userType == 3;

    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('time-block')) return;

        const block = e.target;
        const date = block.dataset.date;
        const time = block.dataset.time;
        const userId = block.dataset.userId;

        modalContent.innerHTML = '<div class="text-center py-4"><span class="text-gray-500">Laden...</span></div>';
        modal.classList.remove('hidden');

        fetch(`/agenda/lesson-data?date=${date}&time=${time}&user_id=${userId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => {
            console.log('Response status:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('Data received:', data);
            renderModalContent(data, date, time, userId);
        })
        .catch(error => {
            console.error('Error fetching lesson data:', error);
            modalContent.innerHTML = '<div class="text-red-500 py-2 px-4">Er is een fout opgetreden bij het laden van de gegevens.</div>';
        });
    });

    function renderModalContent(data, date, time, userId) {
        if (data.hasLesson) {
            // Only show this admin modal to user type 3
            if (!isAdmin) {
                modalContent.innerHTML = `
                    <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Les Details</h2>
                    <div class="flex flex-col gap-2 sm:gap-3">
                        <div class="break-words"><strong class="block sm:inline">Datum en Tijd:</strong> <span class="block sm:inline mt-1 sm:mt-0">${data.les.datum_en_tijd}</span></div>
                        <div class="break-words"><strong class="block sm:inline">Auto:</strong> <span class="block sm:inline mt-1 sm:mt-0">${data.les.auto_name || 'Nog niet toegewezen'}</span></div>
                        <div class="break-words"><strong class="block sm:inline">Leerling:</strong> <span class="block sm:inline mt-1 sm:mt-0">${data.les.leerling || 'Nog niet toegeewzen'}</span></div>
                    </div>
                `;
                return;
            }

            // Admin sees assign/change/delete popup
            modalContent.innerHTML = `
                <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 px-4 sm:px-0">Tijdblok</h2>
                <div class="py-2 px-4 sm:px-0 flex flex-col gap-3 sm:gap-4">
                    <div class="break-words"><strong class="block sm:inline">Datum en Tijd:</strong> <span class="block sm:inline mt-1 sm:mt-0">${data.les.datum_en_tijd}</span></div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Auto</label>
                        <select id="change-car" class="w-full border rounded px-3 py-2.5 text-sm sm:text-base">
                            <option value="">Selecteer auto</option>
                        </select>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button id="update-car-btn" class="w-full sm:w-auto bg-eisgroen hover:bg-eisgroen/80 text-white px-3 py-2.5 rounded text-sm sm:text-base font-medium">Wijzig Auto</button>
                        <button id="delete-block-btn" class="w-full sm:w-auto bg-red-600 hover:bg-red-500 text-white px-3 py-2.5 rounded text-sm sm:text-base font-medium">Verwijder Tijdblok</button>
                    <div id="action-message" class="hidden"></div>
                </div>
            `;

            // Load cars, preselect, and add event listeners...
            loadCars('change-car');
            setTimeout(() => {
                const select = document.getElementById('change-car');
                if (select && data.les.auto) select.value = data.les.auto;
            }, 100);
            document.getElementById('update-car-btn').addEventListener('click', function() {
                const carId = document.getElementById('change-car').value;
                if (!carId) { alert('Selecteer een auto!'); return; }
                assignTimeBlock(date, time, userId, carId);
            });
            document.getElementById('delete-block-btn').addEventListener('click', function() {
                deleteTimeBlock(date, time, userId);
            });

        } else {
            // Unassigned blocks
            if (!isAdmin) {
                modalContent.innerHTML = `<div class="py-2 px-4 sm:px-0 text-gray-600">Dit tijdblok is nog niet beschikbaar.</div>`;
                return;
            }

            // Format the datetime nicely for display
            const dateObj = new Date(date + 'T' + time);
            const formattedDate = dateObj.toLocaleDateString('nl-NL', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            const formattedTime = time;

            modalContent.innerHTML = `
                <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 px-4 sm:px-0">Nieuw Tijdblok Toewijzen</h2>
                <div class="py-2 px-4 sm:px-0 flex flex-col gap-3 sm:gap-4">
                    <div class="break-words"><strong class="block sm:inline">Datum:</strong> <span class="block sm:inline mt-1 sm:mt-0">${formattedDate}</span></div>
                    <div class="break-words"><strong class="block sm:inline">Tijd:</strong> <span class="block sm:inline mt-1 sm:mt-0">${formattedTime}</span></div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Auto</label>
                        <select id="assign-car" class="w-full border rounded px-3 py-2.5 text-sm sm:text-base" style="padding: 0.625rem 0.75rem; border: 1px solid #ccc; border-radius: 0.25rem;">
                            <option value="">Selecteer auto</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button id="assign-block-btn" class="w-full sm:w-auto bg-eisgroen text-white px-3 py-2.5 rounded hover:bg-eisgroen/80 text-sm sm:text-base font-medium">
                            Tijdblok Toewijzen
                        </button>
                    </div>
                    <div id="assign-message" class="hidden"></div>
                </div>
            `;
            
            loadCars('assign-car');
            document.getElementById('assign-block-btn').addEventListener('click', function() {
                const carId = document.getElementById('assign-car').value;
                if (!carId) { 
                    alert('Selecteer eerst een auto!'); 
                    return; 
                }
                assignTimeBlock(date, time, userId, carId);
            });
        }
    }

    function loadCars(selectId = 'assign-car') {
        fetch('/api/cars', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById(selectId);
            if (!select || !data.cars) return;
            select.innerHTML = '<option value="">Selecteer auto</option>';
            data.cars.forEach(car => {
                const opt = document.createElement('option');
                opt.value = car.id;
                opt.textContent = `${car.merk} (${car.kenteken})`;
                select.appendChild(opt);
            });
        });
    }

    function assignTimeBlock(date, time, userId, carId) {
        const messageDiv = document.getElementById('assign-message') || document.getElementById('action-message');
        const btn = document.getElementById('assign-block-btn') || document.getElementById('update-car-btn');
        btn.disabled = true;
        btn.textContent = 'Bezig...';

        const formData = new FormData();
        formData.append('instructeur_id', userId);
        formData.append('date', date);
        formData.append('time', time);
        formData.append('auto', carId);

        fetch('/agenda/assign-timeblock', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => window.location.reload(), 500);
            } else {
                messageDiv.classList.remove('hidden');
                messageDiv.className = 'text-red-600 font-semibold p-2 sm:p-3 bg-red-50 rounded text-sm sm:text-base';
                messageDiv.textContent = data.error || 'Actie mislukt';
                btn.disabled = false;
                btn.textContent = 'Opslaan';
            }
        })
        .catch(() => {
            messageDiv.classList.remove('hidden');
            messageDiv.className = 'text-red-600 font-semibold p-2 sm:p-3 bg-red-50 rounded text-sm sm:text-base';
            messageDiv.textContent = 'Serverfout';
            btn.disabled = false;
            btn.textContent = 'Opslaan';
        });
    }

    function deleteTimeBlock(date, time, userId) {
        const messageDiv = document.getElementById('action-message');
        const btn = document.getElementById('delete-block-btn');
        btn.disabled = true;
        btn.textContent = 'Bezig...';

        const formData = new FormData();
        formData.append('instructeur_id', userId);
        formData.append('date', date);
        formData.append('time', time);

        fetch('/agenda/delete-timeblock', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => window.location.reload(), 500);
            } else {
                messageDiv.classList.remove('hidden');
                messageDiv.className = 'text-red-600 font-semibold p-2 sm:p-3 bg-red-50 rounded text-sm sm:text-base';
                messageDiv.textContent = data.error || 'Verwijderen mislukt';
                btn.disabled = false;
                btn.textContent = 'Verwijderen';
            }
        })
        .catch(() => {
            messageDiv.classList.remove('hidden');
            messageDiv.className = 'text-red-600 font-semibold p-2 sm:p-3 bg-red-50 rounded text-sm sm:text-base';
            messageDiv.textContent = 'Serverfout bij verwijderen';
            btn.disabled = false;
            btn.textContent = 'Verwijderen';
        });
    }

    // Close modal
    [closeBtn, modal].forEach(el => {
        if (!el) return;
        el.addEventListener('click', function(e) {
            if (e.target === modal || e.target === closeBtn) modal.classList.add('hidden');
        });
    });
});