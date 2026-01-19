// Pass autos data from blade to JavaScript
let autos = [];

// Track current car ID for graph data
let currentCarId = null;
let currentCarPeriod = 'week';

// Track main overview period
let currentOverviewPeriod = 'week';

// Chart instances
let overviewChart = null;
let carChart = null;

export function initializeWagenpark(autosData) {
    autos = autosData;
    // Load initial overview graph
    loadOverviewGraphData('week');
}

export function openModal(id) {
    const auto = autos.find(a => a.id === id);
    if (!auto) return;

    currentCarId = id;

    // Populate edit form
    document.getElementById('carId').value = auto.id;
    document.getElementById('merk').value = auto.merk;
    document.getElementById('kenteken').value = auto.kenteken;
    document.getElementById('type').value = auto.type;
    document.getElementById('beschikbaar').value = auto.beschikbaar;
    
    // Populate foto field
    const fotoInput = document.getElementById('foto');
    if (fotoInput) {
        fotoInput.value = auto.foto || '';
        if (window.updateImageButtonText) {
            window.updateImageButtonText('fotoButton', auto.foto || '');
        }
    }

    // Populate inzicht tab
    document.getElementById('carNameInzicht').textContent = auto.merk;
    
    const typeText = auto.type == 1 ? 'Automaat' : 'Handgeschakeld';
    let beschikbaarText = '';
    switch(auto.beschikbaar) {
        case 1: beschikbaarText = 'Beschikbaar'; break;
        case 2: beschikbaarText = 'Bezet'; break;
        case 3: beschikbaarText = 'Onderhoud'; break;
        case 4: beschikbaarText = 'Defect'; break;
    }
    document.getElementById('carDetailsInzicht').textContent = 
        `Kenteken: ${auto.kenteken} | Type: ${typeText} | Status: ${beschikbaarText}`;

    // Clear any previous error messages
    const errorDiv = document.getElementById('errorMessage');
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }

    // Reset to bewerk tab
    switchTab('bewerk');

    // Show modal
    document.getElementById('carModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Load graph data for this car
    loadCarGraphData(id, currentCarPeriod);
}

export function closeModal() {
    document.getElementById('carModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

export function switchTab(tab) {
    const inzichtTab = document.getElementById('tab-inzicht');
    const bewerkTab = document.getElementById('tab-bewerk');
    const inzichtContent = document.getElementById('content-inzicht');
    const bewerkContent = document.getElementById('content-bewerk');

    if (tab === 'bewerk') {
        bewerkTab.classList.add('border-eisgroen', 'text-eisgroen');
        bewerkTab.classList.remove('border-transparent', 'text-gray-500');
        inzichtTab.classList.remove('border-eisgroen', 'text-eisgroen');
        inzichtTab.classList.add('border-transparent', 'text-gray-500');
        
        bewerkContent.classList.remove('hidden');
        inzichtContent.classList.add('hidden');
    } else {
        inzichtTab.classList.add('border-eisgroen', 'text-eisgroen');
        inzichtTab.classList.remove('border-transparent', 'text-gray-500');
        bewerkTab.classList.remove('border-eisgroen', 'text-eisgroen');
        bewerkTab.classList.add('border-transparent', 'text-gray-500');
        
        inzichtContent.classList.remove('hidden');
        bewerkContent.classList.add('hidden');
    }
}

export function switchCarPeriod(period) {
    currentCarPeriod = period;
    
    const buttons = ['car-btn-week', 'car-btn-month', 'car-btn-year'];
    buttons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btnId === `car-btn-${period}`) {
            btn.classList.remove('bg-gray-200', 'text-gray-700');
            btn.classList.add('bg-eisgroen', 'text-white');
        } else {
            btn.classList.remove('bg-eisgroen', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        }
    });

    if (currentCarId) {
        loadCarGraphData(currentCarId, period);
    }
}

export function switchPeriod(period) {
    currentOverviewPeriod = period;
    
    const buttons = ['btn-week', 'btn-month', 'btn-year'];
    buttons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btnId === `btn-${period}`) {
            btn.classList.remove('bg-gray-200', 'text-gray-700');
            btn.classList.add('bg-eisgroen', 'text-white');
        } else {
            btn.classList.remove('bg-eisgroen', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        }
    });

    loadOverviewGraphData(period);
}

async function loadCarGraphData(carId, period) {
    try {
        const response = await fetch(`/autos/${carId}/usage-data?period=${period}`);
        const result = await response.json();
        
        if (result.success) {
            renderCarChart(result.data);
        } else {
            console.error('Failed to load car usage data');
        }
    } catch (error) {
        console.error('Error loading car graph data:', error);
    }
}

async function loadOverviewGraphData(period) {
    try {
        const response = await fetch(`/autos/usage-data?period=${period}`);
        const result = await response.json();
        
        if (result.success) {
            renderOverviewChart(result.data);
        } else {
            console.error('Failed to load overview data');
        }
    } catch (error) {
        console.error('Error loading overview graph data:', error);
    }
}

function renderOverviewChart(data) {
    const container = document.querySelector('.h-80.flex.items-center');
    
    // Replace placeholder with canvas
    if (container) {
        container.innerHTML = '<canvas id="overviewChart"></canvas>';
        container.classList.remove('border-dashed', 'border-gray-300');
        container.classList.add('p-4');
    }
    
    const ctx = document.getElementById('overviewChart');
    if (!ctx) return;
    
    // Destroy existing chart
    if (overviewChart) {
        overviewChart.destroy();
    }
    
    // Create new chart
    overviewChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' uur';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Gebruikstijd (uren)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Datum'
                    }
                }
            }
        }
    });
}

function renderCarChart(data) {
    const container = document.querySelector('#content-inzicht .h-64');
    
    // Replace placeholder with canvas
    if (container) {
        container.innerHTML = '<canvas id="carChart"></canvas>';
        container.classList.remove('border-dashed', 'border-gray-300');
        container.classList.add('p-2');
    }
    
    const ctx = document.getElementById('carChart');
    if (!ctx) return;
    
    // Destroy existing chart
    if (carChart) {
        carChart.destroy();
    }
    
    // Create new chart
    carChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Gebruikstijd: ' + context.parsed.y.toFixed(2) + ' uur';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Uren'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Datum'
                    }
                }
            }
        }
    });
}

export async function submitForm(event) {
    event.preventDefault();
    
    const carId = document.getElementById('carId').value;
    const errorDiv = document.getElementById('errorMessage');
    
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('_method', 'PUT');
    formData.append('merk', document.getElementById('merk').value);
    formData.append('kenteken', document.getElementById('kenteken').value);
    formData.append('type', document.getElementById('type').value);
    formData.append('beschikbaar', document.getElementById('beschikbaar').value);
    formData.append('foto', document.getElementById('foto').value);
    
    try {
        const response = await fetch(`/autos/${carId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            const autoIndex = autos.findIndex(a => a.id == carId);
            if (autoIndex !== -1) {
                autos[autoIndex] = { ...autos[autoIndex], ...data.auto };
            }
            location.reload();
        } else {
            if (errorDiv) {
                errorDiv.textContent = data.message || 'Er is een fout opgetreden bij het opslaan.';
                errorDiv.classList.remove('hidden');
            }
        }
    } catch (error) {
        if (errorDiv) {
            errorDiv.textContent = 'Er is een fout opgetreden bij het opslaan: ' + error.message;
            errorDiv.classList.remove('hidden');
        }
    }
}

export function openAddCarModal() {
    document.getElementById('add_merk').value = '';
    document.getElementById('add_kenteken').value = '';
    document.getElementById('add_type').value = '1';
    document.getElementById('add_beschikbaar').value = '1';
    document.getElementById('add_foto').value = '';
    
    if (window.updateImageButtonText) {
        window.updateImageButtonText('add_fotoButton', '');
    }

    const errorDiv = document.getElementById('addErrorMessage');
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }

    document.getElementById('addCarModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

export function closeAddCarModal() {
    document.getElementById('addCarModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

export async function submitAddCarForm(event) {
    event.preventDefault();
    
    const errorDiv = document.getElementById('addErrorMessage');
    
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('merk', document.getElementById('add_merk').value);
    formData.append('kenteken', document.getElementById('add_kenteken').value);
    formData.append('type', document.getElementById('add_type').value);
    formData.append('beschikbaar', document.getElementById('add_beschikbaar').value);
    formData.append('foto', document.getElementById('add_foto').value);
    
    try {
        const response = await fetch('/autos', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            location.reload();
        } else {
            if (errorDiv) {
                errorDiv.textContent = data.message || 'Er is een fout opgetreden bij het toevoegen.';
                errorDiv.classList.remove('hidden');
            }
        }
    } catch (error) {
        if (errorDiv) {
            errorDiv.textContent = 'Er is een fout opgetreden bij het toevoegen: ' + error.message;
            errorDiv.classList.remove('hidden');
        }
    }
}

export async function removeCar() {
    const id = document.getElementById('carId').value;

    if (!id) {
        console.log("NO CAR FOUND");
        return;
    }

    if (!confirm('Weet je zeker dat je deze auto wilt verwijderen?')) {
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                  || document.querySelector('input[name="_token"]')?.value;

    if (!token) {
        alert('CSRF token niet gevonden');
        return;
    }

    try {
        const response = await fetch(`/autos/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const text = await response.text();
        let data = {};
        try {
            data = JSON.parse(text);
        } catch {}
        
        if (response.ok && data.success) {
            console.log('Auto succesvol verwijderd');
            closeModal();
            location.reload();
        } else {
            alert(data.message || 'Er ging iets mis bij het verwijderen.');
        }
    } catch (error) {
        alert('Er ging iets mis bij het verwijderen: ' + error.message);
    }
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('carModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
    
    const addModal = document.getElementById('addCarModal');
    if (addModal) {
        addModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddCarModal();
            }
        });
    }

    const imageUploaderModal = document.getElementById('imageUploaderModal');
    if (imageUploaderModal) {
        imageUploaderModal.addEventListener('click', function(e) {
            if (e.target === this) {
                if (window.closeImageUploaderModal) {
                    window.closeImageUploaderModal();
                }
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const carModal = document.getElementById('carModal');
            const addCarModal = document.getElementById('addCarModal');
            
            if (carModal && !carModal.classList.contains('hidden')) {
                closeModal();
            }
            if (addCarModal && !addCarModal.classList.contains('hidden')) {
                closeAddCarModal();
            }
        }
    });
});

// Make functions globally available
window.removeCar = removeCar;
window.openModal = openModal;
window.closeModal = closeModal;
window.submitForm = submitForm;
window.initializeWagenpark = initializeWagenpark;
window.switchTab = switchTab;
window.switchCarPeriod = switchCarPeriod;
window.switchPeriod = switchPeriod;
window.openAddCarModal = openAddCarModal;
window.closeAddCarModal = closeAddCarModal;
window.submitAddCarForm = submitAddCarForm;