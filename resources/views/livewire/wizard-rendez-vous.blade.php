
    <div class="max-w-4xl mx-auto py-10">
        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-calendar-check mr-2"></i> Réserver un Rendez-vous
            </h2>

            <!-- Indicateur de progression -->
            <div class="steps mb-6">
                <div class="step {{ $step >= 1 ? 'step-primary' : '' }}">Véhicule</div>
                <div class="step {{ $step >= 2 ? 'step-primary' : '' }}">Service</div>
                <div class="step {{ $step >= 3 ? 'step-primary' : '' }}">Date & Heure</div>
                <div class="step {{ $step >= 4 ? 'step-primary' : '' }}">Validation</div>
            </div>
            @if($step === 1)
                <h3 class="text-lg font-semibold mb-4">Choisissez votre type de véhicule :</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Petite Voiture -->
                    <div class="card bg-base-100 shadow-lg p-4 border {{ $selectedCarType == 'petite_voiture' ? 'border-blue-500' : '' }}">
                        <div class="card-body flex flex-col items-center">
                            <i class="fas fa-car-side text-4xl text-blue-500"></i>
                            <h4 class="text-lg font-semibold text-gray-800">Petite Voiture</h4>
                            <p class="text-sm text-gray-600 text-center mt-2">
                                Exemples : Renault Clio, Peugeot 208, Fiat 500
                            </p>
                            <button wire:click="selectCarType('petite_voiture')" class="btn btn-primary w-full mt-3">
                                @if($selectedCarType == 'petite_voiture') ✅ Sélectionné @else Sélectionner @endif
                            </button>
                        </div>
                    </div>

                    <!-- Berline -->
                    <div class="card bg-base-100 shadow-lg p-4 border {{ $selectedCarType == 'berline' ? 'border-blue-500' : '' }}">
                        <div class="card-body flex flex-col items-center">
                            <i class="fas fa-car text-4xl text-green-500"></i>
                            <h4 class="text-lg font-semibold text-gray-800">Berline</h4>
                            <p class="text-sm text-gray-600 text-center mt-2">
                                Exemples : BMW Série 3, Mercedes Classe C, Audi A4
                            </p>
                            <button wire:click="selectCarType('berline')" class="btn btn-primary w-full mt-3">
                                @if($selectedCarType == 'berline') ✅ Sélectionné @else Sélectionner @endif
                            </button>
                        </div>
                    </div>

                    <!-- SUV / 4x4 -->
                    <div class="card bg-base-100 shadow-lg p-4 border {{ $selectedCarType == 'suv_4x4' ? 'border-blue-500' : '' }}">
                        <div class="card-body flex flex-col items-center">
                            <i class="fas fa-truck-monster text-4xl text-red-500"></i>
                            <h4 class="text-lg font-semibold text-gray-800">SUV / 4x4</h4>
                            <p class="text-sm text-gray-600 text-center mt-2">
                                Exemples : Range Rover Evoque, Toyota RAV4, Nissan X-Trail
                            </p>
                            <button wire:click="selectCarType('suv_4x4')" class="btn btn-primary w-full mt-3">
                                @if($selectedCarType == 'suv_4x4') ✅ Sélectionné @else Sélectionner @endif
                            </button>
                        </div>
                    </div>
                </div>

                <button wire:click.prevent="nextStep"
                        class="btn btn-primary mt-6 w-full"
                        @if(!$selectedCarType) disabled @endif>
                    Suivant
                </button>
            @endif


            <!-- Étape 1 : Sélection du service -->
            @if($step === 2)
                <h3 class="text-lg font-semibold mb-4">Choisissez un service :</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($prestations as $prestation)
                        <div class="card bg-base-100 shadow-lg p-4 border {{ $selectedService == $prestation->id ? 'border-blue-500' : '' }}">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $prestation->service }}</h4>
                            <p class="text-gray-600 text-sm">{{ $prestation->description }}</p>
                            <p class="text-gray-600 text-sm">Durée : {{ $prestation->duree_estimee }}</p>
                            <p class="text-gray-800 font-semibold mt-2">
                                Tarif :
                                {{ match ($selectedCarType) {
                                    'petite_voiture' => $prestation->tarif_petite_voiture,
                                    'berline' => $prestation->tarif_berline,
                                    'suv_4x4' => $prestation->tarif_suv_4x4,
                                    }
                                }}€
                            </p>

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
            @if($step === 3)
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
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($availableSlots as $slot => $isAvailable)
                            @if($isAvailable) {{-- On n'affiche que les créneaux disponibles --}}
                            <button wire:click="selectTime('{{ $slot }}')"
                                    class="btn w-full {{ $selectedTime === $slot ? 'btn-primary' : 'btn-outline' }}">
                                {{ $slot }}
                            </button>
                            @endif
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

                <!-- 🔍 Debug Panel -->
                @if(Auth::user()->role === 'admin')
                <div class="mt-6 p-4 bg-gray-100 border border-gray-300 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">🔍 Debugging Data</h3>

                    <p class="text-sm text-gray-600"><strong>Étape actuelle :</strong> {{ $step }}</p>
                    <p class="text-sm text-gray-600"><strong>Date sélectionnée :</strong> {{ $selectedDate ?? 'Aucune' }}</p>
                    <p class="text-sm text-gray-600"><strong>Créneau sélectionné :</strong> {{ $selectedTime ?? 'Aucun' }}</p>
                    <p class="text-sm text-gray-600"><strong>Durée du service :</strong> {{ $selected_service_duration ?? 'Non défini' }} min</p>

                    <h4 class="text-sm font-semibold mt-2">📅 Jours disponibles :</h4>
                    <pre class="text-xs bg-white p-2 rounded-md border">{{ json_encode($availableDays, JSON_PRETTY_PRINT) }}</pre>

                    <h4 class="text-sm font-semibold mt-2">🕒 Créneaux disponibles :</h4>
                    <pre class="text-xs bg-white p-2 rounded-md border">{{ json_encode($availableSlots, JSON_PRETTY_PRINT) }}</pre>

                    <h4 class="text-sm font-semibold mt-2">🚗 Rendez-vous déjà pris :</h4>
                    <pre class="text-xs bg-white p-2 rounded-md border">{{ json_encode($existingRendezVous ?? [], JSON_PRETTY_PRINT) }}</pre>

                    <h4 class="text-sm font-semibold mt-2">⚠️ Créneaux bloqués :</h4>
                    <pre class="text-xs bg-white p-2 rounded-md border">{{ json_encode($blockedSlots ?? [], JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            @endif


        @if($step === 4)
                <div class="card bg-base-100 p-6">
                    <div class="card-body">

                        <div class="mt-4">
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-tools text-blue-500"></i>
                                <strong>Service :</strong> {{ optional(App\Models\Prestation::find($selectedService))->service }}
                            </p>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-calendar-day text-green-500"></i>
                                <strong>Date :</strong> {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l d F Y') }}
                            </p>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-clock text-yellow-500"></i>
                                <strong>Heure :</strong> {{ $selectedTime }}
                            </p>
                        </div>

                        <!-- Affichage des informations du client -->
                        @auth
                            <div class="mt-6 bg-gray-100 p-4 rounded-lg">
                                <h4 class="text-md font-semibold flex items-center gap-2">
                                    <i class="fas fa-user text-indigo-500"></i> Informations du Client
                                </h4>
                                <p class="text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-id-badge text-gray-500"></i> <strong>Nom :</strong> {{ Auth::user()->name }}
                                </p>
                                <p class="text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-envelope text-gray-500"></i> <strong>Email :</strong> {{ Auth::user()->email }}
                                </p>
                                <p class="text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-phone text-gray-500"></i> <strong>Téléphone :</strong> {{ Auth::user()->phone ?? 'Non renseigné' }}
                                </p>
                            </div>
                        @endauth

                        @guest
                            <div class="mt-6">
                                <h4 class="text-md font-semibold flex items-center gap-2">
                                    <i class="fas fa-user-edit text-indigo-500"></i> Informations du Client
                                </h4>
                                <label for="guest_name" class="label"><span class="label-text">Nom</span></label>
                                <input type="text" wire:model="guest_name" class="input input-bordered w-full" required>

                                <label for="guest_email" class="label mt-2"><span class="label-text">Email</span></label>
                                <input type="email" wire:model="guest_email" class="input input-bordered w-full" required>

                                <label for="guest_phone" class="label mt-2"><span class="label-text">Téléphone</span></label>
                                <input type="text" wire:model="guest_phone" class="input input-bordered w-full">
                            </div>
                        @endguest

                        <div class="flex justify-between mt-6">
                            <button wire:click.prevent="previousStep" class="btn btn-outline btn-secondary flex items-center gap-2">
                                <i class="fas fa-arrow-left"></i> Retour
                            </button>
                            <button wire:click.prevent="saveRendezVous" class="btn btn-success flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Confirmer
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

