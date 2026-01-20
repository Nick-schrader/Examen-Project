document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);

    const agendaModal = document.getElementById('agenda-modal');
    const agendaClose = document.getElementById('agenda-modal-close');

    const verslagModal = document.getElementById('verslag-modal');
    const verslagClose = document.getElementById('verslag-modal-close');

    document.addEventListener('click', function (e) {
        const block = e.target.closest('.time-block');
        if (!block) return;
    
        const date = block.dataset.date;
        const time = block.dataset.time;
        const userId = block.dataset.userId;
        const week = block.dataset.week;
        const year = block.dataset.year;
    
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('date', date);
        urlParams.set('time', time);
        urlParams.set('user', userId);
        urlParams.set('modal', 'les');
        urlParams.set('week', week);
        urlParams.set('year', year);
    
        window.location.search = urlParams.toString();
    });


    // Agenda modal openen op basis van URL
    if (params.get('modal') === 'les' && agendaModal) {
        agendaModal.classList.remove('hidden');

        // URL opschonen (modal param weg)
        setTimeout(() => {
            params.delete('modal');
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }, 100);
    }

    // Sluiten agenda modal
    if (agendaModal && agendaClose) {
        agendaClose.addEventListener('click', () => agendaModal.classList.add('hidden'));
        agendaModal.addEventListener('click', (e) => {
            if (e.target === agendaModal) agendaModal.classList.add('hidden');
        });
    }

    // Verslag modal openen op basis van URL
    if (params.get('modal') === 'verslag' && verslagModal) {
        verslagModal.classList.remove('hidden');
        params.delete('modal');
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }

    // Sluiten verslag modal
    if (verslagModal && verslagClose) {
        verslagClose.addEventListener('click', () => verslagModal.classList.add('hidden'));
        verslagModal.addEventListener('click', (e) => {
            if (e.target === verslagModal) verslagModal.classList.add('hidden');
        });
    }

});