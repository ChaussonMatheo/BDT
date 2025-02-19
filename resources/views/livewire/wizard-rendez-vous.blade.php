
    <div class="max-w-4xl mx-auto py-10">
        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-calendar-check mr-2"></i> Réserver un Rendez-vous
            </h2>

            <!-- Indicateur de progression -->
            <div class="steps mb-6">
                <div class="step {{ $step >= 1 ? 'step-primary' : '' }}">Service</div>
                <div class="step {{ $step >= 2 ? 'step-primary' : '' }}">Date & Heure</div>
                <div class="step {{ $step >= 3 ? 'step-primary' : '' }}">Validation</div>
            </div>

            <!-- Étape 1 : Sélection du service -->
            @if($step === 1)
                <h3 class="text-lg font-semibold mb-4">Choisissez un service :</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($prestations as $prestation)
                        <div class="card bg-base-100 shadow-lg p-4 border {{ $selectedService == $prestation->id ? 'border-blue-500' : '' }}">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $prestation->service }}</h4>
                            <p class="text-gray-600 text-sm">{{ $prestation->description }}</p>
                            <p class="text-gray-800 font-semibold mt-2">Tarif : {{ $prestation->tarif_petite_voiture }}€</p>

                            <button wire:click="selectService({{ $prestation->id }})"
                                    class="btn btn-primary w-full mt-3">
                                @if($selectedService == $prestation->id)
                                    ✅ Sélectionné
                                @else
                                    Sélectionner ce service
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>

                <button wire:click.prevent="nextStep"
                        class="btn btn-primary mt-6 w-full"
                        @if(!$selectedService) disabled @endif>
                    Suivant
                </button>
            @endif

            <!-- Étape 2 : Sélection du jour et créneau -->
            @if($step === 2)
                <h3 class="text-lg font-semibold mb-4">Choisissez un jour :</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($availableDays as $day)
                        <button wire:click="selectDate('{{ $day['value'] }}')"
                                class="btn w-full {{ $selectedDate === $day['value'] ? 'btn-primary' : 'btn-outline' }}">
                            {{ $day['formatted'] }}
                        </button>
                    @endforeach
                </div>

                @if($selectedDate)
                    <h3 class="text-lg font-semibold mt-6 mb-4">Choisissez un créneau :</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($availableSlots as $slot)
                            <button wire:click="selectTime('{{ $slot }}')"
                                    class="btn w-full {{ $selectedTime === $slot ? 'btn-primary' : 'btn-outline' }}">
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 mt-2">Sélectionnez une date pour voir les créneaux disponibles.</p>
                @endif

                <div class="flex justify-between mt-6">
                    <button wire:click.prevent="previousStep" class="btn btn-secondary">Retour</button>
                    <button wire:click.prevent="nextStep"
                            class="btn btn-primary"
                            @if(!$selectedDate || !$selectedTime) disabled @endif>
                        Suivant
                    </button>
                </div>
            @endif

            @if($step === 3)
                <h3 class="text-lg font-semibold">Confirmation du Rendez-vous</h3>

                <p><strong>Service :</strong> {{ optional(App\Models\Prestation::find($selectedService))->service }}</p>
                <p><strong>Date :</strong> {{ $selectedDate }}</p>
                <p><strong>Heure :</strong> {{ $selectedTime }}</p>

                <!-- Demande des informations du client si NON CONNECTÉ -->
                @guest
                    <div class="mt-4">
                        <h4 class="text-md font-semibold">Informations du Client</h4>
                        <label for="guest_name">Nom</label>
                        <input type="text" wire:model="guest_name" class="form-input mt-1 block w-full" required>

                        <label for="guest_email" class="mt-2">Email</label>
                        <input type="email" wire:model="guest_email" class="form-input mt-1 block w-full" required>

                        <label for="guest_phone" class="mt-2">Téléphone</label>
                        <input type="text" wire:model="guest_phone" class="form-input mt-1 block w-full">
                    </div>
                @endguest

                <div class="flex justify-between mt-6">
                    <button wire:click.prevent="previousStep" class="btn btn-secondary">Retour</button>
                    <button wire:click.prevent="saveRendezVous" class="btn btn-success">Confirmer</button>
                </div>
            @endif
        </div>
    </div>

