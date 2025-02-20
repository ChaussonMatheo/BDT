<x-app-layout>
    <div class="flex flex-col min-h-screen bg-gray-100 p-6">
        <x-page-title title="Gérer les Services" breadcrumb="Services" />


        <!-- Barre d'outils -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">

            <!-- Sélecteur de tri -->
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 w-full">
                <label class="font-semibold text-gray-700">Trier par :</label>
                <select id="sortOption" class="select select-bordered w-full md:w-auto">
                    <option value="service">Service</option>
                    <option value="tarif_petite_voiture">Prix Petite Voiture</option>
                    <option value="tarif_berline">Prix Berline</option>
                    <option value="tarif_suv_4x4">Prix SUV/4x4</option>
                    <option value="duree_estimee">Durée</option>
                </select>
            </div>

            <!-- Recherche avec largeur plus grande -->
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 w-full">
                <label class="font-semibold text-gray-700">Rechercher :</label>
                <input type="text" id="searchInput" class="input input-bordered w-full md:w-64 lg:w-80" placeholder="Rechercher une prestation...">
            </div>

            <!-- Bouton d'ajout -->
            <div class="flex justify-end w-full md:w-auto">
                <a href="{{ route('prestations.create') }}" class="btn btn-primary w-full md:w-auto">
                    <i class="fas fa-plus mr-2"></i> Ajouter une prestation
                </a>
            </div>

        </div>



            <!-- Liste des prestations en cartes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="prestationsList">
                @foreach ($prestations as $prestation)
                    <div class="card bg-base-100 shadow-lg p-4 border border-gray-200 prestation-item" data-service="{{ $prestation->service }}"
                         data-tarif-petite-voiture="{{ $prestation->tarif_petite_voiture }}"
                         data-tarif-berline="{{ $prestation->tarif_berline }}"
                         data-tarif-suv-4x4="{{ $prestation->tarif_suv_4x4 }}"
                         data-duree="{{ $prestation->duree_estimee }}">

                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                {{ $prestation->service }}
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

    <!-- Script de tri et recherche -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sortOption = document.getElementById("sortOption");
            const searchInput = document.getElementById("searchInput");
            const prestationsList = document.getElementById("prestationsList");

            sortOption.addEventListener("change", function() {
                let prestations = Array.from(document.querySelectorAll(".prestation-item"));
                let criteria = sortOption.value;

                prestations.sort((a, b) => {
                    let valueA = a.dataset[criteria];
                    let valueB = b.dataset[criteria];

                    if (!isNaN(valueA) && !isNaN(valueB)) {
                        return parseFloat(valueA) - parseFloat(valueB);
                    }
                    return valueA.localeCompare(valueB);
                });

                prestationsList.innerHTML = "";
                prestations.forEach(prestation => prestationsList.appendChild(prestation));
            });

            searchInput.addEventListener("keyup", function() {
                let filter = searchInput.value.toLowerCase();
                let prestations = document.querySelectorAll(".prestation-item");

                prestations.forEach(prestation => {
                    let service = prestation.dataset.service.toLowerCase();
                    prestation.style.display = service.includes(filter) ? "block" : "none";
                });
            });
        });
    </script>

</x-app-layout>
