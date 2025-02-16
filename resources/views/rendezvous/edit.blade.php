<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-calendar-edit mr-2"></i> Modifier le Rendez-vous
            </h2>

            <form method="POST" action="{{ route('rendezvous.update', $rendezVous->id) }}">
                @csrf
                @method('PUT')

                <!-- Étape 1 : Sélection du Service -->
                <h3 class="text-lg font-semibold mb-4">Choisissez un service :</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($prestations as $prestation)
                        <label class="cursor-pointer">
                            <input type="radio" name="prestation_id" value="{{ $prestation->id }}"
                                   class="hidden" {{ $rendezVous->prestation_id == $prestation->id ? 'checked' : '' }}>
                            <div class="card bg-base-100 shadow-md p-4 border
                                        {{ $rendezVous->prestation_id == $prestation->id ? 'border-blue-500' : '' }}">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $prestation->service }}</h4>
                                <p class="text-gray-600 text-sm">{{ $prestation->description }}</p>
                                <p class="text-gray-800 font-semibold mt-2">Tarif : {{ $prestation->tarif_petite_voiture }}€</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Étape 2 : Sélection du Jour -->
                <h3 class="text-lg font-semibold mt-6 mb-4">Choisissez un jour :</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($availableDays as $day)
                        <label class="cursor-pointer">
                            <input type="radio" name="date" value="{{ $day['value'] }}"
                                   class="hidden" {{ $rendezVous->date_heure->toDateString() == $day['value'] ? 'checked' : '' }}>
                            <div class="btn w-full {{ $rendezVous->date_heure->toDateString() == $day['value'] ? 'btn-primary' : 'btn-outline' }}">
                                {{ $day['formatted'] }}
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Étape 3 : Sélection du Créneau Horaire -->
                <h3 class="text-lg font-semibold mt-6 mb-4">Choisissez un créneau :</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($timeSlots as $slot)
                        <label class="cursor-pointer">
                            <input type="radio" name="time" value="{{ $slot }}"
                                   class="hidden" {{ $rendezVous->date_heure->format('H:i') == $slot ? 'checked' : '' }}>
                            <div class="btn w-full {{ $rendezVous->date_heure->format('H:i') == $slot ? 'btn-primary' : 'btn-outline' }}">
                                {{ $slot }}
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Boutons de Validation -->
                <div class="flex justify-between mt-6">
                    <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
