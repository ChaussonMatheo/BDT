<div>
    <h3 class="text-lg font-semibold">Créneaux disponibles :</h3>

    @if($availableSlots)
        <div class="grid grid-cols-3 gap-2 mt-2">
            @foreach($availableSlots as $slot)
                <button
                    wire:click="selectSlot('{{ $slot }}')"
                    class="p-2 text-white rounded {{ $selectedSlot === $slot ? 'bg-green-500' : 'bg-blue-500' }}">
                    {{ $slot }}
                </button>
            @endforeach
        </div>
    @else
        <p class="mt-3 text-red-500">Aucun créneau disponible pour cette date.</p>
    @endif
</div>
