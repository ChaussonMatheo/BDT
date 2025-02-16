export function fetchEvents() {
    const eventList = document.getElementById("event-list");
    if (!eventList) return;

    fetch('/events')
        .then(response => response.json())
        .then(events => {
            eventList.innerHTML = "";
            events.forEach(event => {
                const startDate = new Date(event.start);
                const endDate = event.end ? new Date(event.end) : null;

                const options = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
                const startDateFormatted = startDate.toLocaleDateString('fr-FR', options);
                const startTime = startDate.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

                let durationText = "Durée non définie";
                if (endDate) {
                    const endTime = endDate.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                    const duration = Math.round((endDate - startDate) / (1000 * 60)); // Durée en minutes
                    durationText = `${duration} min (${startTime} - ${endTime})`;
                }

                const listItem = document.createElement("li");
                listItem.className = "p-4 bg-gray-100 rounded-lg flex justify-between items-center shadow-md";
                listItem.innerHTML = `
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">${event.title}</h3>
                        <p class="text-sm text-gray-600">${startDateFormatted}</p>
                        <p class="text-sm text-gray-500">${durationText}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="btn btn-warning btn-sm" onclick="editEvent(${event.id}, '${event.title}', '${event.start}', '${event.end}')">✏️</button>
                        <button class="btn btn-error btn-sm" onclick="deleteEvent(${event.id})">🗑️</button>
                    </div>
                `;
                eventList.appendChild(listItem);
            });
        })
        .catch(error => console.error('Erreur:', error));
}

export function deleteEvent(id) {
    if (confirm("Voulez-vous supprimer cet événement ?")) {
        fetch(`/events/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        })
            .then(() => fetchEvents())
            .catch(error => console.error('Erreur:', error));
    }
}
