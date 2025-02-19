<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if(Auth::user()->role === 'admin')
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Liste des Rendez-Vous
            </h2>
        @else
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Mes Rendez-vous
            </h2>
        @endif

        @if($rendezVous->isEmpty())
            <p class="text-gray-600 text-center">Aucun rendez-vous trouvé.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rendezVous as $index => $rdv)
                    <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                        <!-- En-tête : Numéro du rendez-vous + Date -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-hashtag text-gray-500 mr-2"></i>
                                {{ $index + 1 }} - {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                            </h3>
                        </div>

                        <!-- Détails du rendez-vous -->
                        <div class="space-y-2">
                            <!-- Affichage du nom et email du client -->
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
                                <span class="font-medium">Email :</span>
                                @if($rdv->user)
                                    {{ $rdv->user->email }}
                                @else
                                    {{ $rdv->guest_email ?? 'Non renseigné' }}
                                @endif
                            </p>

                            <!-- Affichage du service -->
                            @if($rdv->prestation)
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-tools text-green-500 mr-2"></i>
                                    <span class="font-medium">Service :</span> {{ $rdv->prestation->service }}
                                </p>
                            @endif

                            <!-- Affichage du statut -->
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
                        </div>

                        <!-- Sélecteur pour modifier le statut -->
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

                                <!-- Bouton Supprimer avec uniquement l'icône -->
                                <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="DELETE">
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
                @endforeach

            </div>
        @endif
    </div>
    <div id="toast-container" class="fixed bottom-5 right-5 flex flex-col space-y-2 z-50"></div>

</x-app-layout>

<!-- Toastr (CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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

                    // Création du toast avec animation
                    let toast = document.createElement("div");
                    toast.className = `alert ${toastType} shadow-lg p-3 opacity-0 translate-y-5 transition-all duration-500`;
                    toast.innerHTML = `<span>${emoji} Statut mis à jour : ${data.statut}</span>`;

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

