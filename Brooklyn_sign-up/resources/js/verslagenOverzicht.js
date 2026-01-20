document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('verslagen-modal');
    const openButton = document.getElementById('open-verslagen');
    const xButton = modal?.querySelector('.x-button');
    const closeButtons = document.querySelectorAll('.verslagen-close-button');

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

    // ⭐ Open modal als ?modal=verslagen in URL staat
    const params = new URLSearchParams(window.location.search);
    if (params.get('modal') === 'verslagen') {
        modal.classList.remove('hidden');

        // ⭐ Verwijder modal=verslagen zodat refresh hem NIET opnieuw opent
        params.delete('modal');
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }
});