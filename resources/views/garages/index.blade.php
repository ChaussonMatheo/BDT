<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-warehouse mr-2"></i> Liste des Garages
        </h2>

        <!-- Bouton d'ajout -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('garages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Ajouter un garage
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
    </div>
</x-app-layout>
