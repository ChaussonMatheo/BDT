<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-calendar-plus mr-2 text-blue-600"></i>
                Créer un rendez-vous (Formulaire annexe)
            </h1>

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('rendezvous.store-annexe') }}" class="space-y-6">
                @csrf

                <!-- Informations client -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                            <i class="fas fa-user mr-2"></i>Informations client
                        </h3>
                    </div>

                    <div>
                        <label for="guest_name" class="block text-sm font-medium text-gray-700">Nom complet *</label>
                        <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="guest_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="guest_phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="tel" name="guest_phone" id="guest_phone" value="{{ old('guest_phone') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="type_de_voiture" class="block text-sm font-medium text-gray-700">Type de voiture</label>
                        <select name="type_de_voiture" id="type_de_voiture"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner...</option>
                            <option value="petite_voiture" {{ old('type_de_voiture') == 'petite_voiture' ? 'selected' : '' }}>Petite voiture</option>
                            <option value="berline" {{ old('type_de_voiture') == 'berline' ? 'selected' : '' }}>Berline</option>
                            <option value="suv_4x4" {{ old('type_de_voiture') == 'suv_4x4' ? 'selected' : '' }}>SUV / 4x4</option>
                        </select>
                    </div>
                </div>

                <!-- Informations rendez-vous -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                            <i class="fas fa-cog mr-2"></i>Détails du rendez-vous
                        </h3>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prestations effectuées *</label>
                        <div id="prestations-container">
                            <!-- Première ligne de prestation -->
                            <div class="prestation-row flex gap-2 mb-2">
                                <div class="flex-1">
                                    <input type="text" name="prestations[0][description]"
                                           placeholder="Ex: Lavage extérieur, Réparation phare, etc."
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                </div>
                                <div class="w-24">
                                    <input type="number" name="prestations[0][montant]" step="0.01" min="0"
                                           placeholder="Prix €"
                                           class="montant-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           oninput="calculateTotal()">
                                </div>
                                <button type="button" onclick="removePrestation(this)" class="btn btn-error btn-sm" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addPrestation()" class="btn btn-secondary btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i> Ajouter une prestation
                        </button>
                    </div>

                    <!-- Suppression du tarif manuel car calculé automatiquement -->
                    <div class="col-span-2">
                        <div class="bg-gray-50 p-3 rounded-md">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Total calculé :</span>
                                <span id="total-display" class="text-lg font-bold text-blue-600">0,00 €</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date *</label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Vous pouvez sélectionner une date passée</p>
                    </div>

                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700">Heure *</label>
                        <select name="time" id="time" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choisir une heure...</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}" {{ old('time') == $slot ? 'selected' : '' }}>
                                    {{ $slot }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('rendezvous.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>
                        Créer le rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let prestationIndex = 1;

        function addPrestation() {
            const container = document.getElementById('prestations-container');
            const newPrestation = document.createElement('div');
            newPrestation.className = 'prestation-row flex gap-2 mb-2';
            newPrestation.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="prestations[${prestationIndex}][description]"
                           placeholder="Ex: Lavage extérieur, Réparation phare, etc."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="w-24">
                    <input type="number" name="prestations[${prestationIndex}][montant]" step="0.01" min="0"
                           placeholder="Prix €"
                           class="montant-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           oninput="calculateTotal()">
                </div>
                <button type="button" onclick="removePrestation(this)" class="btn btn-error btn-sm">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newPrestation);
            prestationIndex++;
            calculateTotal();
        }

        function removePrestation(button) {
            const row = button.closest('.prestation-row');
            row.remove();
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            const montantInputs = document.querySelectorAll('.montant-input');
            montantInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            document.getElementById('total-display').innerText = total.toFixed(2).replace('.', ',') + ' €';
        }

        // Ajouter la classe et l'événement au premier input
        document.addEventListener('DOMContentLoaded', function() {
            const firstMontantInput = document.querySelector('input[name="prestations[0][montant]"]');
            if (firstMontantInput) {
                firstMontantInput.classList.add('montant-input');
                firstMontantInput.addEventListener('input', calculateTotal);
            }
            calculateTotal(); // Calcul initial
        });
    </script>
</x-app-layout>
