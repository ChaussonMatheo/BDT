import { initializeCalendar } from './calendar.js';
import { setupModal } from './modal.js';
import { initGSAPAnimations } from './gsapAnimations.js';
import { initSwiper } from './swiperSetup.js';

document.addEventListener("DOMContentLoaded", function () {
    initializeCalendar();
    setupModal();
    initGSAPAnimations();
    initSwiper();
});
