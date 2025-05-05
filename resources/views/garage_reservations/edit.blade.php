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
    </script>
</x-app-layout>
