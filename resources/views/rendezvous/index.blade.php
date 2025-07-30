<x-app-layout>
    <div class="flex flex-col min-h-screen bg-gray-100 p-6">
        <x-page-title title="Gérer les rendez-vous" breadcrumb="Rendez-vous" />
        <!-- Barre d'outils -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <select id="sortFilter" class="select select-bordered w-full">
                    <option value="date_heure">Date</option>
                    <option value="statut">Statut</option>
                    <option value="service">Service</option>
                    <option value="type_de_voiture">Type de véhicule</option>
                </select>

                <input id="searchFilter" type="text" class="input input-bordered w-full" placeholder="Rechercher...">

                <select id="statusFilter" class="select select-bordered w-full">
                    <option value="all">Tous</option>
                    <option value="confirmé">Confirmé</option>
                    <option value="annulé">Annulé</option>
                    <option value="en attente">En attente</option>
                </select>

                <button id="resetFilters" class="btn btn-secondary">Réinitialiser</button>
            </div>

            <!-- Boutons Ajouter -->
            <div class="flex justify-end w-full sm:w-auto gap-2">
                <a href="{{ route('rendezvous.create') }}" class="btn btn-primary w-full sm:w-auto">
                    <i class="fas fa-plus-circle"></i> Ajouter un rendez-vous
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('rendezvous.create-annexe') }}" class="btn btn-secondary w-full sm:w-auto">
                        <i class="fas fa-calendar-plus"></i> Formulaire Facturation
                    </a>
                @endif
            </div>
        </div>




        @if($rendezVous->isEmpty())
            <p class="text-gray-600 text-center">Aucun rendez-vous trouvé.</p>
        @else
            <!-- Onglets À venir / Passés -->
            <div role="tablist" class="tabs tabs-bordered tabs-sm w-full mb-6 max-w-xs mx-auto flex justify-center">
                <button id="upcomingTab" role="tab" class="tab tab-active text-gray-600">
                    À venir (<span id="upcomingCount">0</span>)
                </button>
                <button id="pastTab" role="tab" class="tab text-gray-600">
                    Passés (<span id="pastCount">0</span>)
                </button>
            </div>

            <!-- Section des rendez-vous -->
            <div id="rendezVousContainer" class="rendezvous-list">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($rendezVous as $index => $rdv)
                        <!-- Début carte rendez-vous -->
                        <div class="rdv-card card bg-base-100 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-200"
                             data-date="{{ $rdv->date_heure }}"
                             data-statut="{{ $rdv->statut }}"
                             data-service="{{ $rdv->prestation ? $rdv->prestation->service : $rdv->prestation_libre }}"
                             data-type-voiture="{{ $rdv->type_de_voiture }}"
                             data-client="{{ $rdv->user ? $rdv->user->name : $rdv->guest_name }}"
                             data-email="{{ $rdv->user ? $rdv->user->email : $rdv->guest_email }}">

                            <!-- En-tête de la carte -->
                            <div class="card-header bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-t-lg border-b">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-0">
                                    <div class="text-center sm:text-left">
                                        <h3 class="text-lg font-bold text-gray-800 flex items-center justify-center sm:justify-start">
                                            <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                            RDV #{{ $rdv->id }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>

                                    <!-- Avatar et statut -->
                                    <div class="flex items-center justify-center sm:justify-end space-x-3">
                                        <!-- Statut badge -->
                                        <span class="badge badge-lg
                                        {{ $rdv->statut === 'confirmé' ? 'badge-success' :
                                           ($rdv->statut === 'annulé' ? 'badge-error' : 'badge-warning') }}">
                                            @if($rdv->statut === 'confirmé')
                                                ✅ Confirmé
                                            @elseif($rdv->statut === 'annulé')
                                                ❌ Annulé
                                            @else
                                                ⏳ En attente
                                            @endif
                                        </span>

                                        <!-- Avatar -->
                                        <div class="relative group">
                                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-lg">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($rdv->user ? $rdv->user->name : $rdv->guest_name) }}&background=random&color=fff" alt="Avatar" class="w-full h-full object-cover">
                                            </div>
                                            <!-- Tooltip nom -->
                                            <div class="absolute top-0 right-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
                                                {{ $rdv->user ? $rdv->user->name : $rdv->guest_name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Corps de la carte -->
                            <div class="card-body p-4 space-y-3">
                                <!-- Informations client -->
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-user text-purple-500 mr-2"></i>
                                        Informations client
                                    </h4>
                                    <div class="space-y-1 text-sm">
                                        <p class="flex items-center">
                                            <span class="font-medium w-16">Nom :</span>
                                            <span class="text-gray-700">{{ $rdv->user ? $rdv->user->name : ($rdv->guest_name ?? 'Non renseigné') }}</span>
                                        </p>
                                        <p class="flex items-center">
                                            <span class="font-medium w-16">Email :</span>
                                            <span class="text-gray-700">{{ $rdv->user ? $rdv->user->email : ($rdv->guest_email ?? 'Non renseigné') }}</span>
                                        </p>
                                        <p class="flex items-center">
                                            <span class="font-medium w-16">Tél :</span>
                                            <span class="text-gray-700">{{ $rdv->user ? $rdv->user->phone : ($rdv->guest_phone ?? 'Non renseigné') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Informations service -->
                                @if($rdv->prestation || $rdv->prestation_libre)
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-tools text-green-500 mr-2"></i>
                                        Prestations
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <p class="text-gray-700">
                                            {{ $rdv->prestation ? $rdv->prestation->service : $rdv->prestation_libre }}
                                        </p>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                @php
                                                    $icons = [
                                                        'petite_voiture' => ['icon' => 'fa-car-side', 'color' => 'text-blue-500', 'label' => 'Petite voiture'],
                                                        'berline' => ['icon' => 'fa-car', 'color' => 'text-indigo-500', 'label' => 'Berline'],
                                                        'suv_4x4' => ['icon' => 'fa-truck-monster', 'color' => 'text-red-500', 'label' => 'SUV / 4x4'],
                                                    ];
                                                    $type = $icons[$rdv->type_de_voiture] ?? ['icon' => 'fa-car', 'color' => 'text-gray-500', 'label' => 'Non spécifié'];
                                                @endphp
                                                <i class="fas {{ $type['icon'] }} {{ $type['color'] }} mr-2"></i>
                                                <span class="text-gray-600">{{ $type['label'] }}</span>
                                            </div>

                                            <div class="text-right">
                                                <span class="text-lg font-bold text-green-600">{{ number_format($rdv->tarif, 2, ',', ' ') }} €</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Actions admin (pied de carte) -->
                            @if(Auth::user()->role === 'admin')
                            <div class="card-footer bg-gray-50 p-4 rounded-b-lg border-t">
                                <!-- Changement de statut -->
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Modifier le statut :</label>
                                    <select class="select select-sm select-bordered w-full"
                                            onchange="changeStatus({{ $rdv->id }}, this.value)">
                                        <option value="en attente" {{ $rdv->statut == 'en attente' ? 'selected' : '' }}>⏳ En attente</option>
                                        <option value="confirmé" {{ $rdv->statut == 'confirmé' ? 'selected' : '' }}>✅ Confirmé</option>
                                        <option value="annulé" {{ $rdv->statut == 'annulé' ? 'selected' : '' }}>❌ Annulé</option>
                                    </select>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="flex flex-wrap justify-between items-center gap-2">
                                    <!-- Actions facture (si prestations libres) -->
                                    @if($rdv->prestation_libre)
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('rendezvous.facture-pdf', $rdv->id) }}" target="_blank"
                                           class="btn btn-info btn-sm tooltip" data-tip="Télécharger PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <button onclick="openEmailModal({{ $rdv->id }})"
                                                class="btn btn-success btn-sm tooltip" data-tip="Envoyer par email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                    @else
                                    <div class="flex-1"></div> <!-- Espace réservé pour aligner le bouton supprimer à droite -->
                                    @endif

                                    <!-- Suppression -->
                                    <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-error btn-sm tooltip"
                                                data-tip="Supprimer"
                                                onclick="return confirm('Supprimer ce rendez-vous ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- Fin carte rendez-vous -->
                    @endforeach
                </div>
            </div>
            <!-- Fin section des rendez-vous -->

    </div>
            @endif
    <div id="toast-container" class="fixed bottom-5 right-5 flex flex-col space-y-2 z-50"></div>

</x-app-layout>

<!-- Toastr (CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchFilter = document.getElementById("searchFilter");
        const sortFilter = document.getElementById("sortFilter");
        const statusFilter = document.getElementById("statusFilter");
        const resetFilters = document.getElementById("resetFilters");

        const upcomingTab = document.getElementById("upcomingTab");
        const pastTab = document.getElementById("pastTab");
        const upcomingCount = document.getElementById("upcomingCount");
        const pastCount = document.getElementById("pastCount");

        let activeTab = 'upcoming'; // Par défaut sur "À venir"

        // Récupérer toutes les cartes de rendez-vous
        const allCards = document.querySelectorAll('.rdv-card');

        // Fonction pour déterminer si un rendez-vous est futur ou passé
        function isUpcoming(dateStr) {
            const rdvDate = new Date(dateStr);
            const now = new Date();
            return rdvDate >= now;
        }

        // Fonction pour filtrer et afficher les rendez-vous
        function filterAndDisplay() {
            const searchTerm = searchFilter.value.toLowerCase();
            const statusValue = statusFilter.value;
            const sortValue = sortFilter.value;

            let visibleCards = [];
            let upcomingCounter = 0;
            let pastCounter = 0;

            allCards.forEach(card => {
                const date = card.dataset.date;
                const statut = card.dataset.statut;
                const service = card.dataset.service || '';
                const client = card.dataset.client || '';
                const email = card.dataset.email || '';
                const typeVoiture = card.dataset.typeVoiture || '';

                const isUpcomingRdv = isUpcoming(date);

                // Compter tous les rendez-vous pour les onglets
                if (isUpcomingRdv) {
                    upcomingCounter++;
                } else {
                    pastCounter++;
                }

                // Filtrer par onglet actif
                const matchesTab = (activeTab === 'upcoming' && isUpcomingRdv) ||
                                  (activeTab === 'past' && !isUpcomingRdv);

                // Filtrer par statut
                const matchesStatus = statusValue === 'all' || statut === statusValue;

                // Filtrer par recherche
                const matchesSearch = searchTerm === '' ||
                                     client.toLowerCase().includes(searchTerm) ||
                                     email.toLowerCase().includes(searchTerm) ||
                                     service.toLowerCase().includes(searchTerm) ||
                                     typeVoiture.toLowerCase().includes(searchTerm);

                if (matchesTab && matchesStatus && matchesSearch) {
                    visibleCards.push(card);
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Trier les cartes visibles
            if (sortValue && visibleCards.length > 0) {
                visibleCards.sort((a, b) => {
                    let aValue, bValue;

                    switch (sortValue) {
                        case 'date_heure':
                            aValue = new Date(a.dataset.date);
                            bValue = new Date(b.dataset.date);
                            return bValue - aValue; // Plus récent en premier
                        case 'statut':
                            aValue = a.dataset.statut;
                            bValue = b.dataset.statut;
                            return aValue.localeCompare(bValue);
                        case 'service':
                            aValue = a.dataset.service || '';
                            bValue = b.dataset.service || '';
                            return aValue.localeCompare(bValue);
                        case 'type_de_voiture':
                            aValue = a.dataset.typeVoiture || '';
                            bValue = b.dataset.typeVoiture || '';
                            return aValue.localeCompare(bValue);
                        default:
                            return 0;
                    }
                });

                // Réorganiser les cartes dans le DOM
                const container = document.querySelector('#rendezVousContainer .grid');
                visibleCards.forEach(card => {
                    container.appendChild(card);
                });
            }

            // Mettre à jour les compteurs
            upcomingCount.textContent = upcomingCounter;
            pastCount.textContent = pastCounter;
        }

        // Gestion des onglets
        upcomingTab.addEventListener('click', function() {
            activeTab = 'upcoming';
            upcomingTab.classList.add('tab-active');
            pastTab.classList.remove('tab-active');
            filterAndDisplay();
        });

        pastTab.addEventListener('click', function() {
            activeTab = 'past';
            pastTab.classList.add('tab-active');
            upcomingTab.classList.remove('tab-active');
            filterAndDisplay();
        });

        // Événements de filtrage
        searchFilter.addEventListener('input', filterAndDisplay);
        statusFilter.addEventListener('change', filterAndDisplay);
        sortFilter.addEventListener('change', filterAndDisplay);

        // Réinitialiser les filtres
        resetFilters.addEventListener('click', function() {
            searchFilter.value = '';
            statusFilter.value = 'all';
            sortFilter.value = 'date_heure';
            filterAndDisplay();
        });

        // Filtrage initial
        filterAndDisplay();
    });
</script>

<!-- Modal pour l'envoi de la facture par email -->
<div id="emailModal" class="modal">
    <div class="modal-box">
        <h2 class="text-lg font-semibold mb-4">Envoyer la facture par Email</h2>
        <form id="emailForm">
            @csrf
            <input type="hidden" name="rdv_id" id="rdv_id_email">
            <div class="mb-4">
                <label for="email_destinataire" class="block text-sm font-medium text-gray-700">Email du destinataire :</label>
                <input type="email" name="email_destinataire" id="email_destinataire" required
                       class="input input-bordered w-full mt-1">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" class="btn btn-secondary" onclick="closeEmailModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Ouvrir le modal pour l'envoi de la facture par email
    function openEmailModal(rdvId) {
        document.getElementById('rdv_id_email').value = rdvId;
        document.getElementById('emailModal').classList.add('modal-open');
    }

    // Fermer le modal
    function closeEmailModal() {
        document.getElementById('emailModal').classList.remove('modal-open');
    }

    // Gestion de l'envoi du formulaire d'email
    document.getElementById('emailForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("rendezvous.envoyer-facture-email") }}', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            closeEmailModal();

            if (data.success) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            closeEmailModal();
            toastr.error('Une erreur est survenue. Veuillez réessayer.');
        });
    });
</script>

