<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Ajouter une prestation
        </h2>

        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('prestations.store') }}" class="space-y-4">
                @csrf

                <!-- Service -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-tools mr-2"></i> Service</span>
                    </label>
                    <input type="text" name="service" class="input input-bordered w-full" required>
                </div>

                <!-- Description -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-file-alt mr-2"></i> Description</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered w-full" required></textarea>
                </div>

                <!-- Tarifs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text"><i class="fas fa-car text-green-500 mr-2"></i> Petite Voiture (€)</span>
                        </label>
                        <input type="number" name="tarif_petite_voiture" class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text"><i class="fas fa-car-side text-yellow-500 mr-2"></i> Berline (€)</span>
                        </label>
                        <input type="number" name="tarif_berline" class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text"><i class="fas fa-truck-pickup text-red-500 mr-2"></i> SUV/4x4 (€)</span>
                        </label>
                        <input type="number" name="tarif_suv_4x4" class="input input-bordered w-full" required>
                    </div>
                </div>

                <!-- Durée estimée -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-clock text-indigo-500 mr-2"></i> Durée estimée</span>
                    </label>
                    <input type="text" name="duree_estimee" class="input input-bordered w-full" required>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('prestations.index') }}" class="btn btn-outline btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
