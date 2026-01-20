document.addEventListener('DOMContentLoaded', function () {

    // URL parameters
    const params = new URLSearchParams(window.location.search);

    // --- Agenda modal ---
    const modal = document.getElementById('agenda-modal');
    const modalContent = document.getElementById('modal-content');
    const closeBtn = document.getElementById('agenda-modal-close');

    const userTypeMeta = document.querySelector('meta[name="user-type"]');
    const userType = userTypeMeta ? userTypeMeta.content : null;
    const isAdmin = userType == 3;

    document.addEventListener('click', function (e) {
        const block = e.target.closest('.time-block');
        if (!block) return;

        const date = block.dataset.date;
        const time = block.dataset.time;
        const userId = block.dataset.userId;

        modalContent.innerHTML = '<div class="text-center py-4"><span class="text-gray-500">Laden...</span></div>';
        modal.classList.remove('hidden');

        fetch(`/agenda/lesson-data?date=${date}&time=${time}&user_id=${userId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            renderModalContent(data, date, time, userId);
        })
        .catch(() => {
            modalContent.innerHTML = '<div class="text-red-500 py-2 px-4">Er is een fout opgetreden bij het laden van de gegevens.</div>';
        });
    });

    function renderModalContent(data, date, time, userId) {
        if (data.hasLesson) {
            if (!isAdmin) {
                // Instructor view - show lesson details with link to verslag
                const les = data.les;
                const autoName = les.auto_merk ? `${les.auto_merk} (${les.kenteken})` : 'Nog niet toegewezen';
                const leerlingNaam = les.leerling_naam || 'Nog niet toegewezen';
                
                modalContent.innerHTML = `
                    <div class="pt-4 flex flex-col gap-3 text-eisblue">
                        <div class="bg-eisgeel rounded-lg p-4 shadow-sm border border-eisgroen/30">
                            <h2 class="text-lg font-semibold text-eisblue mb-3">Lesinformatie</h2>
                            
                            <div class="flex flex-col gap-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-eisblue">Leerling:</span>
                                    <span>${leerlingNaam}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="font-semibold text-eisblue">Adres:</span>
                                    <span>${les.adres || '-'}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="font-semibold text-eisblue">Telefoon:</span>
                                    <span>${les.telefoon || '-'}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="font-semibold text-eisblue">Auto:</span>
                                    <span>${autoName}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="font-semibold text-eisblue">Datum:</span>
                                    <span>${les.datum_en_tijd}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-center mt-6 bg-eisgroen text-white rounded-md p-4 shadow-md hover:bg-[#a3b97f] cursor-pointer">
                            <a href="?modal=verslag&les_id=${les.id}&week=${new URLSearchParams(window.location.search).get('week') || ''}&year=${new URLSearchParams(window.location.search).get('year') || ''}"
                               class="block w-full text-center">
                                Verslag bekijken/toevoegen
                            </a>
                        </div>
                    </div>
                `;
                return;
            }

            // Admin view - show edit options
            modalContent.innerHTML = `
                <h2 class="text-lg font-bold mb-4">Tijdblok</h2>
                <div class="flex flex-col gap-3">
                    <div><strong>Datum en Tijd:</strong> ${data.les.datum_en_tijd}</div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Auto</label>
                        <select id="change-car" class="w-full border rounded px-3 py-2">
                            <option value="">Selecteer auto</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button id="update-car-btn" class="bg-eisgroen text-white px-3 py-2 rounded">Wijzig Auto</button>
                        <button id="delete-block-btn" class="bg-red-600 text-white px-3 py-2 rounded">Verwijder Tijdblok</button>
                    </div>
                    <div id="action-message" class="hidden"></div>
                </div>
            `;

            loadCars('change-car');
            setTimeout(() => {
                const select = document.getElementById('change-car');
                if (select && data.les.auto) select.value = data.les.auto;
            }, 100);

            document.getElementById('update-car-btn').addEventListener('click', function() {
                const carId = document.getElementById('change-car').value;
                if (!carId) return alert('Selecteer een auto!');
                assignTimeBlock(date, time, userId, carId);
            });

            document.getElementById('delete-block-btn').addEventListener('click', function() {
                deleteTimeBlock(date, time, userId);
            });

        } else {
            if (!isAdmin) {
                modalContent.innerHTML = `<div class="text-gray-600">Dit tijdblok is nog niet beschikbaar.</div>`;
                return;
            }

            const dateObj = new Date(date + 'T' + time);
            const formattedDate = dateObj.toLocaleDateString('nl-NL', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            modalContent.innerHTML = `
                <h2 class="text-lg font-bold mb-4">Nieuw Tijdblok Toewijzen</h2>
                <div class="flex flex-col gap-3">
                    <div><strong>Datum:</strong> ${formattedDate}</div>
                    <div><strong>Tijd:</strong> ${time}</div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Auto</label>
                        <select id="assign-car" class="w-full border rounded px-3 py-2">
                            <option value="">Selecteer auto</option>
                        </select>
                    </div>
                    <button id="assign-block-btn" class="bg-eisgroen text-white px-3 py-2 rounded">Tijdblok Toewijzen</button>
                    <div id="assign-message" class="hidden"></div>
                </div>
            `;

            loadCars('assign-car');

            document.getElementById('assign-block-btn').addEventListener('click', function() {
                const carId = document.getElementById('assign-car').value;
                if (!carId) return alert('Selecteer eerst een auto!');
                assignTimeBlock(date, time, userId, carId);
            });
        }
    }

    function loadCars(selectId) {
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
                messageDiv.textContent = data.error || 'Actie mislukt';
                btn.disabled = false;
                btn.textContent = 'Opslaan';
            }
        })
        .catch(() => {
            messageDiv.classList.remove('hidden');
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
                messageDiv.textContent = data.error || 'Verwijderen mislukt';
                btn.disabled = false;
                btn.textContent = 'Verwijderen';
            }
        })
        .catch(() => {
            messageDiv.classList.remove('hidden');
            messageDiv.textContent = 'Serverfout bij verwijderen';
            btn.disabled = false;
            btn.textContent = 'Verwijderen';
        });
    }

    // --- Open modal from URL ---
    if (params.get('modal') === 'les' && modal) {
        modal.classList.remove('hidden');
        params.delete('modal');
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }

    // Close modal
    [closeBtn, modal].forEach(el => {
        if (!el) return;
        el.addEventListener('click', function(e) {
            if (e.target === modal || e.target === closeBtn) modal.classList.add('hidden');
        });
    });

    // --- Verslag modal ---
    const verslagModal = document.getElementById('verslag-modal');
    const verslagClose = document.getElementById('verslag-modal-close');

    if (params.get('modal') === 'verslag' && verslagModal) {
        verslagModal.classList.remove('hidden');
        params.delete('modal');
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }

    if (verslagClose && verslagModal) {
        verslagClose.addEventListener('click', () => verslagModal.classList.add('hidden'));
    }

    if (verslagModal) {
        verslagModal.addEventListener('click', (e) => {
            if (e.target === verslagModal) verslagModal.classList.add('hidden');
        });
    }
});