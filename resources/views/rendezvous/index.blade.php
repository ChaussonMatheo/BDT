<x-app-layout>
    <div class="flex flex-col min-h-screen bg-gray-100 p-6">
        <x-page-title title="Gérer les rendez-vous" breadcrumb="Rendez-vous" />
        <!-- Barre d'outils -->
        <!-- Barre d'outils -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <!-- Tri -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 w-full sm:w-auto">
                <label class="font-semibold text-gray-700">Trier par :</label>
                <select id="sortOption" class="select select-bordered w-full sm:w-auto">
                    <option value="date_heure">Date</option>
                    <option value="statut">Statut</option>
                    <option value="service">Service</option>
                    <option value="type_de_voiture">Type de véhicule</option>
                </select>
            </div>

            <!-- Recherche -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 w-full sm:w-auto">
                <label class="font-semibold text-gray-700">Rechercher :</label>
                <input type="text" id="searchInput" class="input input-bordered w-full sm:w-64 lg:w-80" placeholder="Rechercher un rendez-vous...">
            </div>

            <!-- Filtre par statut -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 w-full sm:w-auto">
                <label class="font-semibold text-gray-700">Statut :</label>
                <select id="statusFilter" class="select select-bordered w-full sm:w-auto">
                    <option value="all">Tous</option>
                    <option value="confirmé">Confirmé</option>
                    <option value="annulé">Annulé</option>
                    <option value="en attente">En attente</option>
                </select>
            </div>

            <!-- Bouton Ajouter -->
            <div class="flex justify-end w-full sm:w-auto">
                <a href="{{ route('rendezvous.create') }}" class="btn btn-primary w-full sm:w-auto">
                    <i class="fas fa-plus-circle"></i> Ajouter un rendez-vous
                </a>
            </div>
        </div>




        @if($rendezVous->isEmpty())
            <p class="text-gray-600 text-center">Aucun rendez-vous trouvé.</p>
        @else
                <div role="tablist" class="tabs tabs-boxed mb-6 tabs-xl w-1/3">
                    <a role="tab" onclick="switchTab('upcoming')" class="tab tab-active">À venir</a>
                    <a role="tab" onclick="switchTab('past')" class="tab">Passés</a>
                </div>
                <!-- Début section des rendez-vous à venir -->
                <div id="upcoming" class="rendezvous-list">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($rendezVous->where('date_heure', '>=', now()) as $index => $rdv)
                            <!-- Début carte rendez-vous -->
                            <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                                <!-- En-tête : Numéro du rendez-vous + Date -->
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-hashtag text-gray-500 mr-2"></i>
                                        {{ $index + 1 }} - {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                                    </h3>

                                    <!-- Avatar du client en haut à droite -->
                                    <div class="relative group">
                                        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 shadow-sm">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rdv->user ? $rdv->user->name : $rdv->guest_name) }}&background=random&color=fff" alt="Avatar">
                                        </div>

                                        <!-- Affichage du nom au survol -->
                                        <div class="absolute top-0 right-12 bg-gray-800 text-white text-sm px-3 py-1 rounded-md opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                            {{ $rdv->user ? $rdv->user->name : $rdv->guest_name }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Début des détails du rendez-vous -->
                                <div class="space-y-2">
                                    <!-- Informations client -->
                                    <p class="text-gray-600 text-sm flex items-center">
                                        <i class="fas fa-user text-purple-500 mr-2"></i>
                                        <span class="font-medium">Client :</span>
                                        @if($rdv->user)
                                            {{ $rdv->user->name }}
                                        @else
                                            {{ $rdv->guest_name ?? 'Non renseigné' }}
                                        @endif
                                    </p>

                                    <p class="text-gray-600 text-sm flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        <span class="font-medium">Email : </span>
                                        @if($rdv->user)
                                            {{ $rdv->user->email }}
                                        @else
                                            {{ $rdv->guest_email ?? 'Non renseigné' }}
                                        @endif
                                    </p>

                                    <p class="text-gray-600 text-sm flex items-center">
                                        <i class="fas fa-phone text-blue-500 mr-2"></i>
                                        <span class="font-medium">Téléphone : </span>
                                        @if($rdv->user)
                                            {{ $rdv->user->phone }}
                                        @else
                                            {{ $rdv->guest_phone ?? 'Non renseigné' }}
                                        @endif
                                    </p>

                                    <!-- Informations service -->
                                    @if($rdv->prestation)
                                        <p class="text-gray-600 text-sm flex items-center">
                                            <i class="fas fa-tools text-green-500 mr-2"></i>
                                            <span class="font-medium">Service : </span> {{ $rdv->prestation->service }}
                                        </p>

                                        <p class="text-gray-600 text-sm flex items-center">
                                            @php
                                                $icons = [
                                                    'petite_voiture' => ['icon' => 'fa-car-side', 'color' => 'text-blue-500', 'label' => 'Petite voiture'],
                                                    'berline' => ['icon' => 'fa-car', 'color' => 'text-indigo-500', 'label' => 'Berline'],
                                                    'suv_4x4' => ['icon' => 'fa-truck-monster', 'color' => 'text-red-500', 'label' => 'SUV / 4x4'],
                                                ];
                                                $type = $icons[$rdv->type_de_voiture] ?? ['icon' => 'fa-car', 'color' => 'text-gray-500', 'label' => 'Non spécifié'];
                                            @endphp

                                            <i class="fas {{ $type['icon'] }} {{ $type['color'] }} mr-2 text-lg"></i>
                                            <span class="font-medium">Type de véhicule :</span>
                                            <span class="{{ $type['color'] }} font-semibold ml-1">{{ $type['label'] }}</span>
                                        </p>

                                        <p class="text-gray-600 text-sm flex items-center">
                                            <i class="fas fa-tag text-red-500 mr-2"></i>
                                            <span class="font-medium">Tarif :</span> {{ $rdv->tarif }} €
                                        </p>
                                    @endif

                                    <!-- Statut -->
                                    @if($rdv->statut)
                                        <p class="text-sm flex items-center font-semibold
                                    {{ $rdv->statut === 'confirmé' ? 'text-green-600' :
                                       ($rdv->statut === 'annulé' ? 'text-red-600' :
                                       ($rdv->statut === 'refusé' ? 'text-gray-600' : 'text-yellow-600')) }}">

                                            @if($rdv->statut === 'confirmé')
                                                ✅ <span class="ml-2">Confirmé</span>
                                            @elseif($rdv->statut === 'annulé')
                                                ❌ <span class="ml-2">Annulé</span>
                                            @else
                                                ⏳ <span class="ml-2">En attente</span>
                                            @endif
                                        </p>
                                    @endif

                                    <!-- Gestion admin -->
                                    @if(Auth::user()->role === 'admin')
                                        <div class="mt-4 flex items-center justify-between">
                                            <div class="w-full">
                                                <label class="block text-sm font-medium text-gray-700">Statut :</label>
                                                <select class="border border-gray-300 p-2 rounded w-full mt-1"
                                                        onchange="changeStatus({{ $rdv->id }}, this.value)">
                                                    <option value="en attente" {{ $rdv->statut == 'en attente' ? 'selected' : '' }}>⏳ En attente</option>
                                                    <option value="confirmé" {{ $rdv->statut == 'confirmé' ? 'selected' : '' }}>✅ Confirmé</option>
                                                    <option value="annulé" {{ $rdv->statut == 'annulé' ? 'selected' : '' }}>❌ Annulé</option>
                                                </select>
                                            </div>

                                            <!-- Suppression -->
                                            <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm mt-6 text-red-500 hover:text-red-700"
                                                        onclick="return confirm('Supprimer ce rendez-vous ?')">
                                                    <i class="fas fa-trash-alt text-xl"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <!-- Fin des détails du rendez-vous -->
                            </div>
                            <!-- Fin carte rendez-vous -->
                        @endforeach
                    </div>
                </div>
                <!-- Fin section des rendez-vous à venir -->
                <!-- Début section des rendez-vous passés -->
                <div id="past" class="rendezvous-list hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($rendezVous->where('date_heure', '<', now()) as $index => $rdv)
                            <!-- Début carte rendez-vous -->
                            <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                                <!-- En-tête : Numéro du rendez-vous + Date -->
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-hashtag text-gray-500 mr-2"></i>
                                        {{ $index + 1 }} - {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                                    </h3>

                                    <!-- Avatar du client en haut à droite -->
                                    <div class="relative group">
                                        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 shadow-sm">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rdv->user ? $rdv->user->name : $rdv->guest_name) }}&background=random&color=fff" alt="Avatar">
                                        </div>

                                        <!-- Affichage du nom au survol -->
                                        <div class="absolute top-0 right-12 bg-gray-800 text-white text-sm px-3 py-1 rounded-md opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                            {{ $rdv->user ? $rdv->user->name : $rdv->guest_name }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Début des détails du rendez-vous -->
                                <div class="space-y-2">
                                    <!-- Informations client -->
                                    <p class="text-gray-600 text-sm flex items-center">
                                        <i class="fas fa-user text-purple-500 mr-2"></i>
                                        <span class="font-medium">Client :</span>
                                        @if($rdv->user)
                                            {{ $rdv->user->name }}
                                        @else
                                            {{ $rdv->guest_name ?? 'Non renseigné' }}
                                        @endif
                                    </p>

                                    <p class="text-gray-600 text-sm flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        <span class="font-medium">Email : </span>
                                        @if($rdv->user)
                                            {{ $rdv->user->email }}
                                        @else
                                            {{ $rdv->guest_email ?? 'Non renseigné' }}
                                        @endif
                                    </p>

                                    <p class="text-gray-600 text-sm flex items-center">
                                        <i class="fas fa-phone text-blue-500 mr-2"></i>
                                        <span class="font-medium">Téléphone : </span>
                                        @if($rdv->user)
                                            {{ $rdv->user->phone }}
                                        @else
                                            {{ $rdv->guest_phone ?? 'Non renseigné' }}
                                        @endif
                                    </p>

                                    <!-- Informations service -->
                                    @if($rdv->prestation)
                                        <p class="text-gray-600 text-sm flex items-center">
                                            <i class="fas fa-tools text-green-500 mr-2"></i>
                                            <span class="font-medium">Service : </span> {{ $rdv->prestation->service }}
                                        </p>

                                        <p class="text-gray-600 text-sm flex items-center">
                                            @php
                                                $icons = [
                                                    'petite_voiture' => ['icon' => 'fa-car-side', 'color' => 'text-blue-500', 'label' => 'Petite voiture'],
                                                    'berline' => ['icon' => 'fa-car', 'color' => 'text-indigo-500', 'label' => 'Berline'],
                                                    'suv_4x4' => ['icon' => 'fa-truck-monster', 'color' => 'text-red-500', 'label' => 'SUV / 4x4'],
                                                ];
                                                $type = $icons[$rdv->type_de_voiture] ?? ['icon' => 'fa-car', 'color' => 'text-gray-500', 'label' => 'Non spécifié'];
                                            @endphp

                                            <i class="fas {{ $type['icon'] }} {{ $type['color'] }} mr-2 text-lg"></i>
                                            <span class="font-medium">Type de véhicule :</span>
                                            <span class="{{ $type['color'] }} font-semibold ml-1">{{ $type['label'] }}</span>
                                        </p>

                                        <p class="text-gray-600 text-sm flex items-center">
                                            <i class="fas fa-tag text-red-500 mr-2"></i>
                                            <span class="font-medium">Tarif :</span> {{ $rdv->tarif }} €
                                        </p>
                                    @endif

                                    <!-- Statut -->
                                    @if($rdv->statut)
                                        <p class="text-sm flex items-center font-semibold
                        {{ $rdv->statut === 'confirmé' ? 'text-green-600' :
                           ($rdv->statut === 'annulé' ? 'text-red-600' :
                           ($rdv->statut === 'refusé' ? 'text-gray-600' : 'text-yellow-600')) }}">

                                            @if($rdv->statut === 'confirmé')
                                                ✅ <span class="ml-2">Confirmé</span>
                                            @elseif($rdv->statut === 'annulé')
                                                ❌ <span class="ml-2">Annulé</span>
                                            @else
                                                ⏳ <span class="ml-2">En attente</span>
                                            @endif
                                        </p>
                                    @endif

                                    <!-- Gestion admin -->
                                    @if(Auth::user()->role === 'admin')
                                        <div class="mt-4 flex items-center justify-between">

                                            <!-- Suppression -->
                                            <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm mt-6 text-red-500 hover:text-red-700"
                                                        onclick="return confirm('Supprimer ce rendez-vous ?')">
                                                    <i class="fas fa-trash-alt text-xl"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <!-- Fin des détails du rendez-vous -->
                            </div>
                            <!-- Fin carte rendez-vous -->
                        @endforeach
                    </div>
                </div>
                <!-- Fin section des rendez-vous passés -->
    </div>
            @endif
    <div id="toast-container" class="fixed bottom-5 right-5 flex flex-col space-y-2 z-50"></div>

</x-app-layout>

<!-- Toastr (CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function switchTab(tab) {
        // Désactiver toutes les listes
        document.querySelectorAll('.rendezvous-list').forEach(el => el.classList.add('hidden'));

        // Activer la bonne liste
        document.getElementById(tab).classList.remove('hidden');

        // Mettre à jour l'onglet actif
        document.querySelectorAll('.tabs a').forEach(el => el.classList.remove('tab-active'));
        document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('tab-active');
    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const sortOption = document.getElementById("sortOption");
        const statusFilter = document.getElementById("statusFilter");

        // Fonction pour filtrer et trier les rendez-vous
        function filterAppointments() {
            let searchText = searchInput.value.toLowerCase();
            let sortValue = sortOption.value;
            let statusValue = statusFilter.value;

            let appointments = document.querySelectorAll(".rendezvous-list .card");

            let sortedAppointments = Array.from(appointments);

            // Appliquer le tri
            sortedAppointments.sort((a, b) => {
                let aValue = getSortValue(a, sortValue);
                let bValue = getSortValue(b, sortValue);

                if (sortValue === "date_heure") {
                    return new Date(aValue) - new Date(bValue);
                } else {
                    return aValue.localeCompare(bValue);
                }
            });

            // Appliquer le filtrage
            sortedAppointments.forEach((card) => {
                let clientName = card.querySelector(".text-gray-600")?.innerText.toLowerCase() || "";
                let status = card.querySelector(".font-semibold")?.innerText.toLowerCase() || "";

                let matchesSearch = clientName.includes(searchText);
                let matchesStatus = statusValue === "all" || status.includes(statusValue);

                if (matchesSearch && matchesStatus) {
                    card.classList.remove("hidden");
                } else {
                    card.classList.add("hidden");
                }
            });

            // Réorganiser les rendez-vous dans le DOM
            let container = document.querySelector(".rendezvous-list .grid");
            container.innerHTML = ""; // Efface tout le contenu
            sortedAppointments.forEach((card) => container.appendChild(card));
        }

        // Fonction pour récupérer la valeur selon l'option de tri
        function getSortValue(card, sortOption) {
            switch (sortOption) {
                case "date_heure":
                    return card.querySelector("h3")?.innerText.split("-")[1]?.trim() || "";
                case "statut":
                    return card.querySelector(".font-semibold")?.innerText.trim() || "";
                case "service":
                    return card.querySelector(".fa-tools + span")?.innerText.trim() || "";
                case "type_de_voiture":
                    return card.querySelector(".fa-car, .fa-truck-monster, .fa-car-side")?.nextSibling?.textContent?.trim() || "";
                default:
                    return "";
            }
        }


        // Écouteurs d'événements
        searchInput.addEventListener("input", filterAppointments);
        sortOption.addEventListener("change", filterAppointments);
        statusFilter.addEventListener("change", filterAppointments);

        // Lancer un premier tri pour l'affichage initial
        filterAppointments();
    });
</script>


<script>
    function changeStatus(id, newStatus) {
        fetch(`/rendezvous/${id}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ statut: newStatus })
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Définition des classes et de l'icône selon le statut
                    let toastType = "alert-info";
                    let emoji = "ℹ️";
                    if (data.statut === "confirmé") {
                        toastType = "alert-success";
                        emoji = "✅";
                    } else if (data.statut === "annulé") {
                        toastType = "alert-error";
                        emoji = "❌";
                    } else if (data.statut === "refusé") {
                        toastType = "alert-warning";
                        emoji = "🚫";
                    }



                    // Ajout au container et animation d’apparition
                    document.getElementById("toast-container").appendChild(toast);
                    setTimeout(() => toast.classList.remove("opacity-0", "translate-y-5"), 100);

                    // Disparition après 3 secondes
                    setTimeout(() => {
                        toast.classList.add("opacity-0", "translate-y-5");
                        setTimeout(() => toast.remove(), 500);
                    }, 3000);
                } else {
                    showErrorToast("❌ Erreur lors de la mise à jour.");
                }
            }).catch(error => {
            console.error('Erreur:', error);
            showErrorToast("❌ Erreur de communication avec le serveur.");
        });
    }

    function showErrorToast(message) {
        let toast = document.createElement("div");
        toast.className = "alert alert-error shadow-lg p-3 opacity-0 translate-y-5 transition-all duration-500";
        toast.innerHTML = `<span>${message}</span>`;

        document.getElementById("toast-container").appendChild(toast);
        setTimeout(() => toast.classList.remove("opacity-0", "translate-y-5"), 100);

        setTimeout(() => {
            toast.classList.add("opacity-0", "translate-y-5");
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>

