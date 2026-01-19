document.addEventListener('DOMContentLoaded', function () {

    // URL parameters (één keer!)
    const params = new URLSearchParams(window.location.search);

    // --- Agenda modal ---
    const modal = document.getElementById('agenda-modal');
    const closeBtn = document.getElementById('agenda-modal-close');

    if (params.get('modal') === 'les' && modal) {
        modal.classList.remove('hidden');
        params.delete('modal');
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }

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
