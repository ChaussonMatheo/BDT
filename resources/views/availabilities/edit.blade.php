<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-edit mr-2"></i> Modifier la disponibilité
        </h2>

        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('availabilities.update', $availability) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Jour de la semaine -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text"><i class="fas fa-calendar-day mr-2"></i> Jour de la semaine</span>
                    </label>
                    <input type="text" name="day_of_week" class="input input-bordered w-full" value="{{ $availability->day_of_week }}" required>
                </div>

                <!-- Horaires -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text"><i class="fas fa-clock mr-2"></i> Heure d'ouverture</span>
                        </label>
                        <input type="time" name="start_time" class="input input-bordered w-full" value="{{ $availability->start_time }}" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text"><i class="fas fa-clock mr-2"></i> Heure de fermeture</span>
                        </label>
                        <input type="time" name="end_time" class="input input-bordered w-full" value="{{ $availability->end_time }}" required>
                    </div>
                </div>

                <!-- Fermeture complète -->
                <div class="form-control">
                    <label class="cursor-pointer flex items-center">
                        <input type="checkbox" name="is_closed" class="checkbox" {{ $availability->is_closed ? 'checked' : '' }}>
                        <span class="ml-2">Fermé toute la journée</span>
                    </label>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('availabilities.index') }}" class="btn btn-outline btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
