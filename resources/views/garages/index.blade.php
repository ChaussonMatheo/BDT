<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-warehouse mr-2"></i> Liste des Garages
        </h2>


        <!-- Boutons d'ajout -->
        <div class="flex justify-end mb-6 gap-2">
            <a href="{{ route('garages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Ajouter un garage
            </a>
            <a href="{{ route('garage-reservations.create') }}" class="btn btn-secondary">
                <i class="fas fa-calendar-plus mr-2"></i> Ajouter réservation garage
            </a>
        </div>

        <!-- Liste des garages -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($garages as $garage)
                <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <!-- Nom du Garage -->
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-building text-blue-600 mr-2"></i> {{ $garage->nom }}
                        </h3>

                        <!-- Utilisateur associé -->
                        @if($garage->user)
                            <div class="tooltip tooltip-left" data-tip="{{ $garage->user->name }}">
                                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($garage->user->name) }}&background=random&color=fff" alt="Avatar">
                                    </div>
                                </label>
                            </div>
                        @endif
                    </div>

                    <!-- Informations -->
                    <p class="text-gray-600 text-sm flex items-center mt-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> {{ $garage->lieu }}
                    </p>
                    <p class="text-gray-600 text-sm flex items-center">
                        <i class="fas fa-phone text-green-500 mr-2"></i> {{ $garage->telephone }}
                    </p>

                    <!-- Actions -->
                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('garages.edit', $garage->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <form action="{{ route('garages.destroy', $garage->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Supprimer ce garage ?')">
                                <i class="fas fa-trash-alt mr-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="divider my-10">
            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i> Réservations garage
        </div>

        <!-- Liste des réservations -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @forelse ($reservations as $reservation)
                <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-700">
                        📍 {{ $reservation->garage->nom }}
                    </h4>
                    <p class="text-sm text-gray-600 mt-1">
                        Du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}
                        au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}
                    </p>

                    <ul class="mt-2 space-y-1 text-sm text-gray-700">
                        @foreach ($reservation->prestations as $prestation)
                            <li class="flex justify-between">
                                <span>{{ $prestation->description }}</span>
                                <span class="font-medium">{{ number_format($prestation->montant, 2, ',', ' ') }} €</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-3 flex justify-end gap-2">
                        <a href="{{ route('garage-reservations.edit', $reservation->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <a href="{{ route('garage-reservations.facture', $reservation->id) }}" target="_blank" class="btn btn-neutral btn-sm">
                            <i class="fas fa-file-invoice mr-2"></i> Facture PDF
                        </a>
                        <button onclick="openEmailModal({{ $reservation->id }})" class="btn btn-info btn-sm">
                            <i class="fas fa-envelope mr-2"></i> Envoyer par email
                        </button>
                    </div>

                </div>
            @empty
                <p class="text-gray-500 col-span-3">Aucune réservation enregistrée.</p>
            @endforelse
        </div>
    </div> <!-- fin du grid des garages -->

    <!-- Modal pour envoyer la facture par email -->
    <div id="emailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Envoyer la facture par email</h3>
                <form id="emailForm" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 text-left">Adresse email :</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEmailModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEmailModal(reservationId) {
            document.getElementById('emailModal').classList.remove('hidden');
            document.getElementById('emailForm').action = `/reservations-garage/${reservationId}/envoyer-facture`;
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
            document.getElementById('email').value = '';
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEmailModal();
            }
        });
    </script>

    </div>
</x-app-layout>
