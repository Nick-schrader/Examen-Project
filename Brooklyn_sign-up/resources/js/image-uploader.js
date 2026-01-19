// Global variables
let currentImages = [];
let selectedImageField = null;

// Open image uploader modal
function openImageUploaderModal(fieldId = 'foto') {
    selectedImageField = fieldId;
    const modal = document.getElementById('imageUploaderModal');
    modal.classList.remove('hidden');
    loadCarImages();
}

// Close image uploader modal
function closeImageUploaderModal() {
    const modal = document.getElementById('imageUploaderModal');
    modal.classList.add('hidden');
    selectedImageField = null;
}

// base path url
let imageBaseUrl = '/assets/cars';

// Load all car images from server
async function loadCarImages() {
    try {
        const response = await fetch('/autos/images');
        const data = await response.json();
        
        if (data.success) {
            currentImages = data.images;
            // Update base URL if provided
            if (data.public_url) {
                imageBaseUrl = data.public_url;
            }
            displayImages();
        }

    } catch (error) {
        console.error('Error loading images:', error);
        const container = document.getElementById('imageGalleryContainer');
        if (container) {
            container.innerHTML = `
                <div class="col-span-full text-center py-8 text-red-500">
                    Fout bij laden van afbeeldingen
                </div>
            `;
        }
    }
}

// Display images in the modal
function displayImages() {
    const container = document.getElementById('imageGalleryContainer');
    
    // Check if container has been made
    if (!container) {
        console.error('Image gallery container not found');
        return;
    }
    
    // If no images
    if (!currentImages || currentImages.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8 text-gray-500">
                Geen afbeeldingen gevonden
            </div>
        `;
        return;
    }
    
    // Display the images in html
    container.innerHTML = currentImages.map(image => `
        <div class="relative group cursor-pointer border-2 border-transparent hover:border-eisblue rounded-lg overflow-hidden transition-all duration-200" 
             data-image="${image}">
            <img src="${imageBaseUrl}/${image}" 
                 alt="${image}" 
                 class="w-full h-32 object-cover"
                 onerror="console.error('Failed to load image:', '${imageBaseUrl}/${image}'); this.style.backgroundColor='#f3f4f6'; this.alt='Afbeelding niet gevonden';">
            <div class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                <span class="text-white opacity-0 group-hover:opacity-100 font-semibold">Selecteer</span>
            </div>
        </div>
    `).join('');
    
    // Add click event listeners to all image cards
    container.querySelectorAll('[data-image]').forEach(card => {
        card.addEventListener('click', function() {
            const imageName = this.getAttribute('data-image');
            selectImage(imageName);
        });
    });
}

// Select an image
function selectImage(imageName) {
    // Update the hidden input field
    const hiddenInput = document.getElementById(selectedImageField);
    if (hiddenInput) {
        hiddenInput.value = imageName;
    }
    
    // Update the button text to show selected image
    const buttonId = selectedImageField === 'foto' ? 'fotoButton' : 'add_fotoButton';
    console.log("Button id: " + buttonId);
    console.log("Selected Image Field: " + selectedImageField);
    console.log("Image name: " + imageName);

    updateImageButtonText(buttonId, imageName);
    
    closeImageUploaderModal();
}

// Update button text to show selected image
function updateImageButtonText(buttonId, imageName) {
    const button = document.getElementById(buttonId);
    if (button) {
        if (imageName) {
            button.textContent = imageName;
            button.classList.remove('text-gray-500');
            button.classList.add('text-gray-900', 'font-medium');
        } else {
            button.textContent = 'Selecteer een foto';
            button.classList.remove('text-gray-900', 'font-medium');
            button.classList.add('text-gray-500');
        }
    }
}

// Handle file upload
async function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Check file size (2MB max)
    if (file.size > 2 * 1024 * 1024) {
        showUploadMessage('Bestand is te groot (max 2MB)', 'error');
        event.target.value = '';
        return;
    }
    
    // Check file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
        showUploadMessage('Alleen JPG, JPEG en PNG bestanden zijn toegestaan', 'error');
        event.target.value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('image', file);
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                  || document.querySelector('input[name="_token"]')?.value;
    
    if (!token) {
        showUploadMessage('CSRF token niet gevonden', 'error');
        return;
    }
    
    // add functionality to upload image button
    try {
        const uploadButton = document.getElementById('uploadImageBtn');
        const originalText = uploadButton.innerHTML;
        uploadButton.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Uploaden...';
        uploadButton.disabled = true;
        
        const response = await fetch('/autos/upload-image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload images to show the new one
            await loadCarImages();
            
            // Clear the file input
            event.target.value = '';
            
            // Show success message
            showUploadMessage(data.message || 'Afbeelding succesvol geüpload!', 'success');
            
            // Automatically select the newly uploaded image
            if (data.filename) {
                selectImage(data.filename);
            }
        } else {
            showUploadMessage(data.message || 'Upload mislukt', 'error');
        }
        
        uploadButton.innerHTML = originalText;
        uploadButton.disabled = false;
        
    } catch (error) {
        console.error('Error uploading image:', error);
        showUploadMessage('Er is een fout opgetreden bij het uploaden', 'error');
        
        const uploadButton = document.getElementById('uploadImageBtn');
        uploadButton.innerHTML = '+ Nieuwe Foto Uploaden';
        uploadButton.disabled = false;
        
        // Clear the file input
        event.target.value = '';
    }
}

// Show upload message
function showUploadMessage(message, type) {
    const messageDiv = document.getElementById('uploadMessage');
    if (!messageDiv) return;
    
    messageDiv.textContent = message;
    messageDiv.className = `p-3 rounded-md text-sm ${
        type === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'
    }`;
    messageDiv.classList.remove('hidden');
    
    setTimeout(() => {
        messageDiv.classList.add('hidden');
    }, 3000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Image uploader initialized');
    
    // Set initial button text for both forms
    updateImageButtonText('fotoButton', '');
    updateImageButtonText('add_fotoButton', '');
    
    // Add click event listeners to the buttons
    const fotoButton = document.getElementById('fotoButton');
    if (fotoButton) {
        fotoButton.addEventListener('click', function() {
            openImageUploaderModal('foto');
        });
    }
    
    const addFotoButton = document.getElementById('add_fotoButton');
    if (addFotoButton) {
        addFotoButton.addEventListener('click', function() {
            openImageUploaderModal('add_foto');
        });
    }
    
    // Add event listener to close modal button
    const closeButtons = document.querySelectorAll('#imageUploaderModal button[onclick*="closeImageUploaderModal"]');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', closeImageUploaderModal);
    });
    
    // Add event listener to upload input
    const uploadInput = document.getElementById('imageUploadInput');
    if (uploadInput) {
        uploadInput.addEventListener('change', handleImageUpload);
    }
    
    // Pre-populate the edit form button if there's an existing image
    const existingImage = document.getElementById('foto')?.value;
    if (existingImage) {
        updateImageButtonText('fotoButton', existingImage);
    }
});

// Export functions to window for backwards compatibility with inline onclick
window.openImageUploaderModal = openImageUploaderModal;
window.closeImageUploaderModal = closeImageUploaderModal;
window.handleImageUpload = handleImageUpload;
window.updateImageButtonText = updateImageButtonText;