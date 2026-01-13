document.addEventListener('DOMContentLoaded', function () {

    // TODO: Make timeblocks visible again on big screen if they were previously toggled on small screen

    // Open modal on timeblock click
    document.querySelectorAll('.timeblock').forEach(function (block) {
        block.addEventListener('click', function () {
            document.getElementById('agenda-modal').classList.remove('hidden');
        });
    });
    
    // Close modal
    var modalClose = document.getElementById('agenda-modal-close');
    if (modalClose) {
        modalClose.addEventListener('click', function () {
            document.getElementById('agenda-modal').classList.add('hidden');
        });
    }
    // Close modal when clicking outside modal content
    var agendaModal = document.getElementById('agenda-modal');
    if (agendaModal) {
        agendaModal.addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    // Collapsible days for small devices
    document.querySelectorAll('.day-toggle').forEach(function (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            var dayIndex = this.getAttribute('data-day-index');
            var content = document.querySelector('.day-content[data-day-index="' + dayIndex + '"]');
            if (content) {
                content.classList.toggle('hidden');
            }
            var icon = this.querySelector('.toggle-icon');
            if (icon) {
                icon.classList.toggle('rotate-180');
            }
        });
    });
    
    // By default, collapse all but the first day on small screens
    if (window.innerWidth < 768) {
        document.querySelectorAll('.day-content').forEach(function (content, idx) {
            if (idx !== 0) {
                content.classList.add('hidden');
            }
        });
    }
    else
    {
        document.querySelectorAll('.day-content').forEach(function (content) {
            content.classList.remove('hidden');
        });
    }
});
