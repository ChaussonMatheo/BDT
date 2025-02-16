<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            Liste des Prestations
        </h2>

        <!-- Bouton d'ajout -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('prestations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Ajouter une prestation
            </a>
        </div>

        <!-- Liste des prestations en cartes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($prestations as $prestation)
                <div class="card bg-base-100 shadow-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-tools text-blue-600 mr-2"></i> {{ $prestation->service }}
                        </h3>
                    </div>

                    <p class="text-gray-600 text-sm mt-2">{{ $prestation->description }}</p>

                    <!-- Tarifs -->
                    <div class="mt-4">
                        <p class="text-sm text-gray-700 flex items-center">
                            <i class="fas fa-car text-green-500 mr-2"></i> Petite Voiture:
                            <span class="font-bold ml-2">{{ $prestation->tarif_petite_voiture }} €</span>
                        </p>
                        <p class="text-sm text-gray-700 flex items-center">
                            <i class="fas fa-car-side text-yellow-500 mr-2"></i> Berline:
                            <span class="font-bold ml-2">{{ $prestation->tarif_berline }} €</span>
                        </p>
                        <p class="text-sm text-gray-700 flex items-center">
                            <i class="fas fa-truck-pickup text-red-500 mr-2"></i> SUV/4x4:
                            <span class="font-bold ml-2">{{ $prestation->tarif_suv_4x4 }} €</span>
                        </p>
                    </div>

                    <!-- Durée estimée -->
                    <p class="text-sm text-gray-500 mt-4 flex items-center">
                        <i class="fas fa-clock text-indigo-500 mr-2"></i> Durée :
                        <span class="font-bold ml-2"> {{ $prestation->duree_estimee }} min</span>
                    </p>

                    <!-- Boutons d'action -->
                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('prestations.edit', $prestation->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <form action="{{ route('prestations.destroy', $prestation->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Supprimer cette prestation ?')">
                                <i class="fas fa-trash-alt mr-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
