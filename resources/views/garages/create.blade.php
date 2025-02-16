<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Ajouter un garage
        </h2>

        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('garages.store') }}" class="space-y-4">
                @csrf

                <!-- Nom du Garage -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-warehouse mr-2"></i> Nom du Garage</span>
                    </label>
                    <input type="text" name="nom" class="input input-bordered w-full" required>
                </div>

                <!-- Lieu -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-map-marker-alt mr-2"></i> Lieu</span>
                    </label>
                    <input type="text" name="lieu" class="input input-bordered w-full" required>
                </div>

                <!-- Numéro de Téléphone -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-phone mr-2"></i> Numéro de Téléphone</span>
                    </label>
                    <input type="text" name="telephone" class="input input-bordered w-full" required>
                </div>

                <!-- Sélection de l'utilisateur -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-user mr-2"></i> Assigné à l'utilisateur</span>
                    </label>
                    <select name="user_id" class="select select-bordered w-full">
                        <option value="">Aucun</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('garages.index') }}" class="btn btn-outline btn-secondary">
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
