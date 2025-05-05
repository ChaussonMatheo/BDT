<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Nouvelle réservation garage</h2>

        <form method="POST" action="{{ route('garage-reservations.store') }}">
            @csrf

            <div class="mb-4">
                <label class="label">Garage</label>
                <select name="garage_id" class="select select-bordered w-full">
                    @foreach($garages as $garage)
                        <option value="{{ $garage->id }}">{{ $garage->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="label">Date de début</label>
                    <input type="date" name="start_date" class="input input-bordered w-full" required>
                </div>
                <div class="flex-1">
                    <label class="label">Date de fin</label>
                    <input type="date" name="end_date" class="input input-bordered w-full" required>
                </div>
            </div>

            <div>
                <label class="label">Prestations</label>
                <div id="prestations-wrapper" class="space-y-2">
                    <div class="flex gap-2">
                        <input type="text" name="prestations[0][description]" placeholder="Description" class="input input-bordered w-full" required>
                        <input type="number" name="prestations[0][montant]" step="0.01" placeholder="Montant (€)" class="input input-bordered w-32" required>
                    </div>
                </div>
                <button type="button" onclick="addPrestation()" class="btn btn-sm mt-2">+ Ajouter une prestation</button>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

    <script>
        let prestationIndex = 1;

        function addPrestation() {
            const wrapper = document.getElementById('prestations-wrapper');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2');
            div.innerHTML = `
                <input type="text" name="prestations[${prestationIndex}][description]" placeholder="Description" class="input input-bordered w-full" required>
                <input type="number" name="prestations[${prestationIndex}][montant]" step="0.01" placeholder="Montant (€)" class="input input-bordered w-32" required>
            `;
            wrapper.appendChild(div);
            prestationIndex++;
        }
    </script>
</x-app-layout>
