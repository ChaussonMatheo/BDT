import { fetchEvents } from './events.js';

export function setupModal() {
    const eventModal = document.getElementById('event-modal');
    const eventForm = document.getElementById("event-form");

    if (!eventModal || !eventForm) return;

    eventForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const title = document.getElementById("event-title").value;
        const start = document.getElementById("event-start").value;
        const end = document.getElementById("event-end").value || start;

        fetch('/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title, start, end })
        })
            .then(response => response.json())
            .then(() => {
                eventModal.close();
                fetchEvents();
            })
            .catch(error => console.error('Erreur:', error));
    });
}

export function editEvent(id, title, start) {
    document.getElementById("event-title").value = title;
    document.getElementById("event-start").value = start;
    document.getElementById('event-modal').showModal();

    document.getElementById("event-form").onsubmit = function (e) {
        e.preventDefault();
        fetch(`/events/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: document.getElementById("event-title").value,
                start: document.getElementById("event-start").value
            })
        })
            .then(() => {
                document.getElementById('event-modal').close();
                fetchEvents();
            })
            .catch(error => console.error('Erreur:', error));
    };
}
