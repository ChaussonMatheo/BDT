<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-edit mr-2"></i> Modifier le garage
        </h2>

        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('garages.update', $garage->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nom du Garage -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-warehouse mr-2"></i> Nom du Garage</span>
                    </label>
                    <input type="text" name="nom" class="input input-bordered w-full" value="{{ $garage->nom }}" required>
                </div>

                <!-- Lieu -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-map-marker-alt mr-2"></i> Lieu</span>
                    </label>
                    <input type="text" name="lieu" class="input input-bordered w-full" value="{{ $garage->lieu }}" required>
                </div>

                <!-- Numéro de Téléphone -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-phone mr-2"></i> Numéro de Téléphone</span>
                    </label>
                    <input type="text" name="telephone" class="input input-bordered w-full" value="{{ $garage->telephone }}" required>
                </div>

                <!-- Sélection de l'utilisateur -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-user mr-2"></i> Assigné à l'utilisateur</span>
                    </label>
                    <div class="flex items-center space-x-4">
                        <!-- Avatar utilisateur -->
                        @if($garage->user)
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($garage->user->name) }}&background=random&color=fff" alt="Avatar">
                                </div>
                            </label>
                        @endif

                        <!-- Liste déroulante pour sélectionner l'utilisateur -->
                        <select name="user_id" class="select select-bordered w-full">
                            <option value="">Aucun</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $garage->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('garages.index') }}" class="btn btn-outline btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
