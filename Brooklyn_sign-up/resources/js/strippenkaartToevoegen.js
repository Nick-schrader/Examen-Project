document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('strippenkaart-modal');
    const openButton = document.getElementById('open-strippenkaart');
    const xButton = modal?.querySelector('.x-button');
    const closeButtons = document.querySelectorAll('.strippenkaart-close-button');

    if (!modal || !openButton || !xButton) return;

    openButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Klik buiten het modal-content sluit modal
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Knoppen in de modal sluiten modal
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });

    // X-knop sluit modal
    xButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
});
