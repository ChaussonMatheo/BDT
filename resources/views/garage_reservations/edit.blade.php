<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Modifier la réservation garage</h2>

        <form method="POST" action="{{ route('garage-reservations.update', $reservation->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="label">Garage</label>
                <select name="garage_id" class="select select-bordered w-full" required>
                    @foreach($garages as $garage)
                        <option value="{{ $garage->id }}" {{ $reservation->garage_id == $garage->id ? 'selected' : '' }}>
                            {{ $garage->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="label">Date de début</label>
                    <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($reservation->start_date)->toDateString() }}" class="input input-bordered w-full" required>
                </div>
                <div class="flex-1">
                    <label class="label">Date de fin</label>
                    <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($reservation->end_date)->toDateString() }}" class="input input-bordered w-full" required>
                </div>
            </div>

            <div>
                <label class="label">Prestations</label>
                <div id="prestations-wrapper" class="space-y-2">
                    @foreach ($reservation->prestations as $index => $prestation)
                        <div class="flex gap-2 items-center">
                            <input type="hidden" name="prestations[{{ $index }}][id]" value="{{ $prestation->id }}">
                            <input type="text" name="prestations[{{ $index }}][description]" class="input input-bordered w-full" value="{{ $prestation->description }}" required>
                            <input type="number" name="prestations[{{ $index }}][montant]" step="0.01" class="input input-bordered w-32" value="{{ $prestation->montant }}" required>
                            <button type="button" onclick="removePrestation(this)" class="btn btn-error btn-sm"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addPrestation()" class="btn btn-sm mt-2">+ Ajouter une prestation</button>
            </div>
            <div class="mb-6">
                <label class="label">Couleur de la réservation</label>

                <input type="hidden" name="couleur" id="selected-color" value="{{ $reservation->couleur ?? '#2196f3' }}">

                <div class="flex flex-wrap gap-2 mt-2">
                    @php
                        $colors = ['#2196f3', '#4caf50', '#ff9800', '#f44336', '#9c27b0', '#3f51b5', '#795548', '#00bcd4', '#e91e63', '#607d8b'];
                    @endphp

                    @foreach ($colors as $color)
                        <div
                            class="w-8 h-8 rounded-full cursor-pointer border-2 border-transparent hover:scale-110 transition-all"
                            style="background-color: {{ $color }};"
                            onclick="selectColor('{{ $color }}', this)"
                            data-color="{{ $color }}"
                        ></div>
                    @endforeach
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

    <script>
        let prestationIndex = {{ count($reservation->prestations) }};

        function addPrestation() {
            const wrapper = document.getElementById('prestations-wrapper');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2', 'items-center');
            div.innerHTML = `
                <input type="text" name="prestations[${prestationIndex}][description]" placeholder="Description" class="input input-bordered w-full" required>
                <input type="number" name="prestations[${prestationIndex}][montant]" step="0.01" placeholder="Montant (€)" class="input input-bordered w-32" required>
                <button type="button" onclick="removePrestation(this)" class="btn btn-error btn-sm"><i class="fas fa-trash-alt"></i></button>
            `;
            wrapper.appendChild(div);
            prestationIndex++;
        }

        function removePrestation(button) {
            button.closest('div').remove();
        }

        function selectColor(color, element) {
            document.getElementById('selected-color').value = color;

            // Retirer la bordure des autres ronds
            document.querySelectorAll('[data-color]').forEach(el => {
                el.classList.remove('ring-2', 'ring-offset-2', 'ring-gray-900');
            });

            // Ajouter une bordure au rond sélectionné
            element.classList.add('ring-2', 'ring-offset-2', 'ring-gray-900');
        }

        // Sélectionner automatiquement la couleur enregistrée à l'affichage
        document.addEventListener('DOMContentLoaded', () => {
            const currentColor = document.getElementById('selected-color').value;
            const selected = [...document.querySelectorAll('[data-color]')]
                .find(el => el.dataset.color.toLowerCase() === currentColor.toLowerCase());

            if (selected) {
                selectColor(currentColor, selected);
            }
        });
    </script>
</x-app-layout>
