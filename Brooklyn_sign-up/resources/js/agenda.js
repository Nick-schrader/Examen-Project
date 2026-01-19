document.addEventListener('DOMContentLoaded', function () {
    // Elementen ophalen (één keer)
    const modal = document.getElementById('agenda-modal');
    const closeBtn = document.getElementById('agenda-modal-close');

    // --- Modal auto-open via URL param (modal=les) ---
    (function handleModalParam() {
        if (!modal) return;
        const params = new URLSearchParams(window.location.search);
        if (params.get('modal') === 'les') {
            modal.classList.remove('hidden');

            // Verwijder modal param zodat refresh hem niet opnieuw opent
            params.delete('modal');
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }
    })();

    // --- Sluit modal via knop ---
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function () {
            modal.classList.add('hidden');
        });
    }

    // --- Klik buiten modal sluit hem ---
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }

    // --- Collapsible days voor kleine schermen ---
    document.querySelectorAll('.day-toggle').forEach(function (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const dayIndex = this.getAttribute('data-day-index');
            const content = document.querySelector('.day-content[data-day-index="' + dayIndex + '"]');
            if (content) content.classList.toggle('hidden');

            const icon = this.querySelector('.toggle-icon');
            if (icon) icon.classList.toggle('rotate-180');
        });
    });

    // --- Standaard collapse gedrag op small screens ---
    if (window.innerWidth < 768) {
        document.querySelectorAll('.day-content').forEach(function (content, idx) {
            if (idx !== 0) content.classList.add('hidden');
        });
    } else {
        document.querySelectorAll('.day-content').forEach(function (content) {
            content.classList.remove('hidden');
        });
    }
});
