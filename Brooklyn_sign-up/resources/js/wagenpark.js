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
    
    // Populate foto field - IMPORTANT FIX
    const fotoInput = document.getElementById('foto');
    if (fotoInput) {
        fotoInput.value = auto.foto || '';
        // Update the button text to show current image
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
        bewerkTab.classList.add('border-eisgroen', 'text-eisgroen');
        bewerkTab.classList.remove('border-transparent', 'text-gray-500');
        inzichtTab.classList.remove('border-eisgroen', 'text-eisgroen');
        inzichtTab.classList.add('border-transparent', 'text-gray-500');
        
        // Show bewerk content
        bewerkContent.classList.remove('hidden');
        inzichtContent.classList.add('hidden');
    } else {
        // Activate inzicht tab
        inzichtTab.classList.add('border-eisgroen', 'text-eisgroen');
        inzichtTab.classList.remove('border-transparent', 'text-gray-500');
        bewerkTab.classList.remove('border-eisgroen', 'text-eisgroen');
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
            btn.classList.add('bg-eisgroen', 'text-white');
        } else {
            btn.classList.remove('bg-eisgroen', 'text-white');
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
            btn.classList.add('bg-eisgroen', 'text-white');
        } else {
            btn.classList.remove('bg-eisgroen', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        }
    });

    // Load graph data for overview
    loadOverviewGraphData(period);
}

function loadCarGraphData(carId, period) {
    // This function will load graph data for a specific car
    console.log(`Loading graph data for car ${carId} with period ${period}`);
}

function loadOverviewGraphData(period) {
    // This function will load graph data for all cars overview
    console.log(`Loading overview graph data with period ${period}`);
}

export async function submitForm(event) {
    event.preventDefault();
    
    const carId = document.getElementById('carId').value;
    const errorDiv = document.getElementById('errorMessage');
    
    // Hide error message
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
    
    // Create FormData and explicitly add all fields including foto
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('_method', 'PUT');
    formData.append('merk', document.getElementById('merk').value);
    formData.append('kenteken', document.getElementById('kenteken').value);
    formData.append('type', document.getElementById('type').value);
    formData.append('beschikbaar', document.getElementById('beschikbaar').value);
    formData.append('foto', document.getElementById('foto').value); // IMPORTANT: Include foto
    
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
    
    // Reset the button text
    if (window.updateImageButtonText) {
        window.updateImageButtonText('add_fotoButton', '');
    }

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
    
    const errorDiv = document.getElementById('addErrorMessage');
    
    // Hide error message
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
    
    // Create FormData and explicitly add all fields including foto
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('merk', document.getElementById('add_merk').value);
    formData.append('kenteken', document.getElementById('add_kenteken').value);
    formData.append('type', document.getElementById('add_type').value);
    formData.append('beschikbaar', document.getElementById('add_beschikbaar').value);
    formData.append('foto', document.getElementById('add_foto').value); // IMPORTANT: Include foto
    
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

export function removeCar() {
    // Get car id
    const id = document.getElementById('carId').value;

    // Check if car exists
    if (!id) {
        console.log("NO CAR FOUND");
        return;
    }
    console.log("CAR WILL BE REMOVED:", id);

    // Get crsf token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Call remove url
    fetch(`/autos/remove/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Delete failed');
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        alert(data.message);
        
        // Example: remove car row from page
        document.getElementById(`car-${id}`)?.remove();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Er ging iets mis bij het verwijderen.');
    });
}


// Initialize event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Close modals when clicking outside
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
                closeImageUploaderModal();
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