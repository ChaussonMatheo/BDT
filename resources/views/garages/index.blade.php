<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if(Auth::user()->role === 'admin')
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Liste des Rendez-Vous
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rendezVous as $rdv)
                    <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                        <!-- En-tête : Client et date -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                            </h3>

                            <!-- Utilisateur (Client) -->
                            @if($rdv->user)
                                <div class="tooltip tooltip-left" data-tip="{{ $rdv->user->name }}">
                                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rdv->user->name) }}&background=random&color=fff" alt="Avatar">
                                        </div>
                                    </label>
                                </div>
                            @endif
                        </div>

                        <!-- Détails du rendez-vous -->
                        <div class="space-y-2">
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-user text-purple-500 mr-2"></i>
                                <span class="font-medium">Client :</span> {{ $rdv->user->name ?? 'Non assigné' }}
                            </p>

                            @if($rdv->garage)
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-building text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Garage :</span> {{ $rdv->garage->nom }}
                                </p>
                            @endif

                            @if($rdv->prestation)
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-tools text-green-500 mr-2"></i>
                                    <span class="font-medium">Service :</span> {{ $rdv->prestation->service }}
                                </p>
                            @endif
                        </div>

                        <!-- Sélecteur pour modifier le statut -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Statut :</label>
                            <select class="border border-gray-300 p-2 rounded w-full mt-1" onchange="updateStatus({{ $rdv->id }}, this)">
                                <option value="en attente" {{ $rdv->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmé" {{ $rdv->statut == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                                <option value="annulé" {{ $rdv->statut == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                <option value="refusé" {{ $rdv->statut == 'refusé' ? 'selected' : '' }}>Refusé</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex justify-end">
                            <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Supprimer ce rendez-vous ?')">
                                    <i class="fas fa-trash-alt mr-2"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>

<!-- Script AJAX pour mise à jour du statut -->
<script>
    function updateStatus(id, selectElement) {
        let statut = selectElement.value;

        fetch(`/rendezvous/${id}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ statut: statut })
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Statut mis à jour !');
                } else {
                    alert('Erreur lors de la mise à jour.');
                }
            }).catch(error => console.error('Erreur:', error));
    }
</script>
