import { initializeCalendar } from './calendar.js';
import { setupModal } from './modal.js';

// Initialisation du calendrier et du formulaire modale
document.addEventListener('DOMContentLoaded', function () {
    initializeCalendar();
    setupModal();
});
