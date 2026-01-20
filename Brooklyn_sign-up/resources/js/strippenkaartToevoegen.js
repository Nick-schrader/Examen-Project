document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('strippenkaart-modal');
    const openButton = document.getElementById('open-strippenkaart');
    const xButton = modal?.querySelector('.x-button');
    const closeButtons = document.querySelectorAll('.strippenkaart-close-button');

    if (!modal || !openButton || !xButton) return;

    // Openen via knop
    openButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Sluiten bij klik buiten content
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Sluiten via knoppen
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });

    // Sluiten via X
    xButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    //  Open modal als ?modal=strippenkaart in URL staat
    const params = new URLSearchParams(window.location.search);
    if (params.get('modal') === 'strippenkaart') {
        modal.classList.remove('hidden');

        //  Verwijder modal=strippenkaart zodat refresh hem NIET opnieuw opent
        params.delete('modal');
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }
});