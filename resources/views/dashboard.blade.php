<x-app-layout>
    <div class="flex flex-col min-h-screen bg-gray-100 p-6">

        <!-- Header -->
        <x-page-title title="Tableau de Bord" breadcrumb="Tableau de Bord" />


        <!-- Stats Cards -->
        <div class="stats shadow w-full grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <i class="fas fa-calendar-check text-4xl"></i>
                </div>
                <div class="stat-title">Total Rendez-vous</div>
                <div class="stat-value text-primary">{{ $totalRendezVous }}</div>
                <div class="stat-desc">Tous les rendez-vous enregistrés</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-green-500">
                    <i class="fas fa-check-circle text-4xl"></i>
                </div>
                <div class="stat-title">Confirmés</div>
                <div class="stat-value text-green-500">{{ $confirmedRendezVous }}</div>
                <div class="stat-desc">Rendez-vous validés</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-red-500">
                    <i class="fas fa-times-circle text-4xl"></i>
                </div>
                <div class="stat-title">Annulés</div>
                <div class="stat-value text-red-500">{{ $cancelledRendezVous }}</div>
                <div class="stat-desc">Rendez-vous supprimés</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-yellow-500">
                    <i class="fas fa-calendar-week text-4xl"></i>
                </div>
                <div class="stat-title">Cette semaine</div>
                <div class="stat-value text-yellow-500">{{ $rendezVousLastWeek }}</div>
                <div class="stat-desc">RDV programmés cette semaine</div>
            </div>
        </div>

        <!-- Calendrier des Rendez-vous -->
        <div class="card bg-base-100 shadow p-6 mt-8">
            <h3 class="text-xl font-semibold mb-4 flex items-center text-primary">
                <i class="fas fa-calendar-alt mr-2"></i> Calendrier
            </h3>
            <div class="overflow-auto">
                <div id="calendar-loader" class="flex justify-center items-center my-6 hidden">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                </div>
                <div id="calendar" class="max-w-full max-h-screen mx-auto"></div>
            </div>
        </div>

    </div>
    <input type="checkbox" id="modal-detail-rdv" class="modal-toggle" />
    <div class="modal" id="modal-container-rdv">
        <div class="modal-box w-full max-w-2xl">
            <h3 class="font-bold text-lg mb-4" id="rdv-title">Détail du rendez-vous</h3>
            <div class="space-y-2 text-sm">
                <p><strong>Date :</strong> <span id="rdv-date"></span></p>
                <p><strong>Client :</strong> <span id="rdv-client"></span></p>
                <p><strong>Email :</strong> <span id="rdv-email"></span></p>
                <p><strong>Téléphone :</strong> <span id="rdv-telephone"></span></p>
                <p><strong>Prestation :</strong> <span id="rdv-prestation"></span></p>
                <p><strong>Voiture :</strong> <span id="rdv-voiture"></span></p>
                <p><strong>Tarif :</strong> <span id="rdv-tarif"></span></p>
                <p><strong>Statut :</strong> <span id="rdv-statut" class="badge badge-outline"></span></p>
            </div>
            <div class="modal-action">
                <label for="modal-detail-rdv" class="btn">Fermer</label>
            </div>
        </div>
    </div>
    <input type="checkbox" id="modal-detail-garage" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4" id="garage-title">Détail de la réservation garage</h3>

            <div class="space-y-1 text-sm">
                <p><strong>Garage :</strong> <span id="garage-nom"></span></p>
                <p><strong>Adresse :</strong> <span id="garage-lieu"></span></p>
                <p><strong>Période :</strong> <span id="garage-periode"></span></p>
                <p><strong>Prestations :</strong></p>
                <ul id="garage-prestations" class="list-disc list-inside text-sm text-gray-700"></ul>
                <p class="font-semibold mt-2">Total TTC : <span id="garage-total" class="text-base"></span></p>
            </div>
            <div class="modal-action justify-between items-center">
                <div class="flex gap-2">
                    <a id="garage-edit-link" href="#" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Modifier
                    </a>
                    <a id="garage-pdf-link" href="#" class="btn btn-neutral btn-sm" target="_blank">
                        <i class="fas fa-file-invoice mr-1"></i> Facture PDF
                    </a>
                </div>
                <label for="modal-detail-garage" class="btn btn-outline btn-sm">Fermer</label>
            </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

        <style>
            /* Adapter FullCalendar aux couleurs DaisyUI */
            .fc {
                @apply text-sm;
            }

            .fc-toolbar-title {
                @apply text-lg font-bold text-primary;
            }

            .fc-button {
                @apply bg-primary text-white border-none rounded px-3 py-1 hover:bg-primary-focus;
            }

            .fc-button:disabled {
                @apply opacity-50 cursor-not-allowed;
            }

            .fc-daygrid-event {
                @apply bg-primary text-white rounded shadow p-1;
            }

            .fc-daygrid-event:hover {
                @apply bg-primary-focus;
            }

            .fc-daygrid-day-number {
                @apply font-semibold;
            }

            .fc-day-today {
                @apply bg-primary bg-opacity-10;
            }

            .fc-scrollgrid {
                @apply rounded border border-gray-200;
            }

            .fc-col-header-cell-cushion {
                @apply font-medium;
            }
        </style>


        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar/locales/fr.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            if (!calendarEl) {
                console.error("⚠️ Élément #calendar introuvable !");
                return;
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'fr',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },

                events: function(fetchInfo, successCallback, failureCallback) {
                    const loader = document.getElementById('calendar-loader');
                    loader.classList.remove('hidden'); // afficher le spinner

                    Promise.all([
                        fetch('/api/rendezvous').then(res => res.json()),
                        fetch('/api/garage-reservations').then(res => res.json())
                    ])
                        .then(([rdvs, garages]) => {
                            const events = [...rdvs, ...garages];
                            successCallback(events);
                            loader.classList.add('hidden'); // cacher le spinner
                        })
                        .catch(err => {
                            console.error("Erreur lors du chargement des événements :", err);
                            failureCallback(err);
                            loader.classList.add('hidden'); // cacher aussi en cas d'erreur
                        });
                },

                eventContent: function(arg) {
                    const props = arg.event.extendedProps;
                    const isGarage = props.type === 'garage';

                    if (isGarage) {
                        return {
                            html: `
                <div class="bg-blue-600 p-1 rounded text-xs">
                    <div class="font-semibold">🔧 Réservation garage</div>
                    <div>${props.garage}</div>
                </div>
            `
                        };
                    }

                    // RDV classique (code inchangé)
                    const title = arg.event.title;
                    const client = props.client;
                    const voiture = props.voiture;
                    const statut = props.statut;
                    const bgColor = arg.event.backgroundColor || props.color;

                    let iconClass = '';
                    let badgeColor = '';
                    switch (statut) {
                        case 'Confirmé':
                            iconClass = 'fa-circle-check text-green-500';
                            badgeColor = 'badge-success';
                            break;
                        case 'Annulé':
                            iconClass = 'fa-circle-xmark text-red-500';
                            badgeColor = 'badge-error';
                            break;
                        default:
                            iconClass = 'fa-clock text-blue-500';
                            badgeColor = 'badge-info';
                            break;
                    }

                    return {
                                    html: `
                    <div class="p-1 rounded-md shadow-sm text-xs leading-tight space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid ${iconClass} text-sm"></i>
                            <span class="font-semibold">${title}</span>
                        </div>
                        <div class="text-gray-700">${client}</div>
                        <div class="text-gray-500 italic">${voiture}</div>
                        <div><span class="badge ${badgeColor} text-[10px]">${statut}</span></div>
                    </div>
                    `
                    };
                },


                eventClick: function(info) {
                    const id = info.event.id;
                    const props = info.event.extendedProps;

                    if (props.type === 'garage') {
                        // cas réservation garage
                        const numericId = id.replace('garage-', '');
                        fetch(`/api/garage-reservations/${numericId}`)
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('garage-edit-link').href = `/reservations-garage/${numericId}/edit`;
                                document.getElementById('garage-pdf-link').href = `/reservations-garage/${numericId}/facture`;
                                document.getElementById('garage-title').textContent = `Réservation – ${data.garage}`;
                                document.getElementById('garage-nom').textContent = data.garage;
                                document.getElementById('garage-lieu').textContent = data.lieu ?? 'Non renseignée';
                                document.getElementById('garage-periode').textContent = `Du ${formatDate(data.start)} au ${formatDate(data.end)}`;

                                const ul = document.getElementById('garage-prestations');
                                ul.innerHTML = '';
                                data.prestations.forEach(p => {
                                    const li = document.createElement('li');
                                    li.textContent = `${p.description} – ${p.montant}`;
                                    ul.appendChild(li);
                                });

                                document.getElementById('garage-total').textContent = data.total;
                                document.getElementById('modal-detail-garage').checked = true;
                            });
                    } else {
                        // RDV client classique
                        const rdvId = info.event.id;
                        fetch(`/api/rendezvous/${rdvId}`)
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('rdv-title').textContent = `Détail du RDV #${data.id}`;
                                document.getElementById('rdv-date').textContent = data.date_heure;
                                document.getElementById('rdv-client').textContent = data.client;
                                document.getElementById('rdv-email').textContent = data.email;
                                document.getElementById('rdv-telephone').textContent = data.telephone;
                                document.getElementById('rdv-prestation').textContent = data.prestation;
                                document.getElementById('rdv-voiture').textContent = data.voiture;
                                document.getElementById('rdv-tarif').textContent = data.tarif;
                                document.getElementById('rdv-statut').textContent = data.statut;

                                document.getElementById('modal-detail-rdv').checked = true;
                            });
                    }
                }

            });
            function formatDate(dateStr) {
                const d = new Date(dateStr);
                return d.toLocaleDateString('fr-FR');
            }

            calendar.render();
        });
    </script>



</x-app-layout>
