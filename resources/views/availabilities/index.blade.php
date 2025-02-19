<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center gap-2">
            <i class="fas fa-calendar-alt text-primary"></i> Gestion des Disponibilités
        </h2>

        <!-- Bouton d'ajout -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('availabilities.create') }}" class="btn btn-primary flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Ajouter une disponibilité
            </a>
        </div>

        <!-- Grid Layout pour afficher les disponibilités sous forme de cartes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($availabilities as $availability)
                <div class="card bg-white shadow-md rounded-lg border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                         {{ ucfirst($availability->day_of_week) }}
                    </h3>

                    <p class="text-gray-700 flex items-center gap-2 mt-2">
                        <i class="fas fa-clock text-yellow-500"></i>
                        <strong>Horaires :</strong> {{ $availability->start_time }} - {{ $availability->end_time }}
                    </p>

                    <div class="flex justify-between items-center mt-4">
                        <div class="flex items-center gap-2">
                            <!-- Statut avec icône dynamique -->
                            @if ($availability->is_closed)
                                <span class="badge badge-error flex items-center gap-1">
                                    <i class="fas fa-times-circle"></i> Fermé
                                </span>
                            @else
                                <span class="badge badge-success flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Ouvert
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('availabilities.edit', $availability) }}" class="btn btn-warning btn-sm flex items-center gap-1">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Bouton de suppression avec modal -->
                            <button onclick="openDeleteModal({{ $availability->id }})"
                                    class="btn btn-error btn-sm flex items-center gap-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal de suppression -->
    <div id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg"><i class="fas fa-exclamation-triangle text-red-500"></i> Confirmation</h3>
            <p class="py-4">Voulez-vous vraiment supprimer cette disponibilité ? Cette action est irréversible.</p>
            <div class="modal-action">
                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error">Oui, Supprimer</button>
                </form>
                <label for="delete-modal" class="btn">Annuler</label>
            </div>
        </div>
    </div>

    <!-- Script pour ouvrir le modal de suppression dynamiquement -->
    <script>
        function openDeleteModal(id) {
            let deleteForm = document.getElementById('delete-form');
            deleteForm.action = "/availabilities/" + id;
            document.getElementById('delete-modal').classList.add("modal-open");
        }
    </script>
</x-app-layout>
