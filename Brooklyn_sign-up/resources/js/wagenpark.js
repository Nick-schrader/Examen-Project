// Pass autos data from blade to JavaScript
let autos = [];

// Track current car ID for graph data
let currentCarId = null;
let currentCarPeriod = 'week';

// Track main overview period
let currentOverviewPeriod = 'week';

export function initializeWagenpark(autosData) {
    autos = autosData;
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

    // Reset to bewerk tab (now first)
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
    // Update tab buttons
    const inzichtTab = document.getElementById('tab-inzicht');
    const bewerkTab = document.getElementById('tab-bewerk');
    const inzichtContent = document.getElementById('content-inzicht');
    const bewerkContent = document.getElementById('content-bewerk');

    if (tab === 'bewerk') {
        // Activate bewerk tab
        bewerkTab.classList.add('border-eisblue', 'text-eisblue');
        bewerkTab.classList.remove('border-transparent', 'text-gray-500');
        inzichtTab.classList.remove('border-eisblue', 'text-eisblue');
        inzichtTab.classList.add('border-transparent', 'text-gray-500');
        
        // Show bewerk content
        bewerkContent.classList.remove('hidden');
        inzichtContent.classList.add('hidden');
    } else {
        // Activate inzicht tab
        inzichtTab.classList.add('border-eisblue', 'text-eisblue');
        inzichtTab.classList.remove('border-transparent', 'text-gray-500');
        bewerkTab.classList.remove('border-eisblue', 'text-eisblue');
        bewerkTab.classList.add('border-transparent', 'text-gray-500');
        
        // Show inzicht content
        inzichtContent.classList.remove('hidden');
        bewerkContent.classList.add('hidden');
    }
}

export function switchCarPeriod(period) {
    currentCarPeriod = period;
    
    // Update button states
    const buttons = ['car-btn-week', 'car-btn-month', 'car-btn-year'];
    buttons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btnId === `car-btn-${period}`) {
            btn.classList.remove('bg-gray-200', 'text-gray-700');
            btn.classList.add('bg-eisblue', 'text-white');
        } else {
            btn.classList.remove('bg-eisblue', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        }
    });

    // Reload graph data for current car
    if (currentCarId) {
        loadCarGraphData(currentCarId, period);
    }
}

export function switchPeriod(period) {
    currentOverviewPeriod = period;
    
    // Update button states
    const buttons = ['btn-week', 'btn-month', 'btn-year'];
    buttons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btnId === `btn-${period}`) {
            btn.classList.remove('bg-gray-200', 'text-gray-700');
            btn.classList.add('bg-eisblue', 'text-white');
        } else {
            btn.classList.remove('bg-eisblue', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        }
    });

    // Load graph data for overview
    loadOverviewGraphData(period);
}

function loadCarGraphData(carId, period) {
    // This function will load graph data for a specific car
    // You'll need to implement the backend endpoint and graph rendering
    console.log(`Loading graph data for car ${carId} with period ${period}`);
    
    // TODO: Fetch data from backend and render graph
    // Example:
    // fetch(`/api/autos/${carId}/usage?period=${period}`)
    //     .then(response => response.json())
    //     .then(data => renderCarGraph(data))
    //     .catch(error => console.error('Error loading car data:', error));
}

function loadOverviewGraphData(period) {
    // This function will load graph data for all cars overview
    console.log(`Loading overview graph data with period ${period}`);
    
    // TODO: Fetch data from backend and render graph
    // Example:
    // fetch(`/api/autos/overview?period=${period}`)
    //     .then(response => response.json())
    //     .then(data => renderOverviewGraph(data))
    //     .catch(error => console.error('Error loading overview data:', error));
}

export async function submitForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const carId = document.getElementById('carId').value;
    const errorDiv = document.getElementById('errorMessage');
    
    // Hide error message
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
    
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
        console.log('Response:', data);

        if (response.ok) {
            // Update the auto in the autos array
            const autoIndex = autos.findIndex(a => a.id == carId);
            if (autoIndex !== -1) {
                autos[autoIndex] = { ...autos[autoIndex], ...data.auto };
            }
            
            // Reload the page to show updated data
            location.reload();
        } else {
            console.error('Error response:', data);
            // Show error message
            if (errorDiv) {
                errorDiv.textContent = data.message || 'Er is een fout opgetreden bij het opslaan.';
                errorDiv.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        // Show error message
        if (errorDiv) {
            errorDiv.textContent = 'Er is een fout opgetreden bij het opslaan: ' + error.message;
            errorDiv.classList.remove('hidden');
        }
    }
}

export function openAddCarModal() {
    // Clear form fields
    document.getElementById('add_merk').value = '';
    document.getElementById('add_kenteken').value = '';
    document.getElementById('add_type').value = '1';
    document.getElementById('add_beschikbaar').value = '1';
    document.getElementById('add_foto').value = '';

    // Clear any previous error messages
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
    
    const formData = new FormData(event.target);
    const errorDiv = document.getElementById('addErrorMessage');
    
    // Hide error message
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
    
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
        console.log('Response:', data);

        if (response.ok) {
            // Reload the page to show the new car
            location.reload();
        } else {
            console.error('Error response:', data);
            // Show error message
            if (errorDiv) {
                errorDiv.textContent = data.message || 'Er is een fout opgetreden bij het toevoegen.';
                errorDiv.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        // Show error message
        if (errorDiv) {
            errorDiv.textContent = 'Er is een fout opgetreden bij het toevoegen: ' + error.message;
            errorDiv.classList.remove('hidden');
        }
    }
}

// Initialize event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    const modal = document.getElementById('carModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }

    // Close add car modal when clicking outside
    const addModal = document.getElementById('addCarModal');
    if (addModal) {
        addModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddCarModal();
            }
        });
    }

    // Close modal with Escape key
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

export function closeImageUploaderModal() {
    document.getElementById('imageUploaderModal').classList.add('hidden');
    document.body.style.overflow = 'hidden';
}

export function openImageUploaderModal() {
    document.getElementById('imageUploaderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

export function removeCar() {
    console.log("CAR WILL BE REMOVED");
}

// Make functions globally available
window.removeCar = removeCar;
window.openImageUploaderModal = openImageUploaderModal;
window.closeImageUploaderModal = closeImageUploaderModal;
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