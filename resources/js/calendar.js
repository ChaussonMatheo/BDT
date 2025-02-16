import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';
import { fetchEvents } from './events.js';

export function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        locale: frLocale, // Mettre FullCalendar en français
        events: '/events',
        dateClick: function (info) {
            document.getElementById('event-modal').showModal();
            document.getElementById("event-start").value = info.dateStr;
        }
    });

    calendar.render();
    fetchEvents();
}
