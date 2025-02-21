<div>
    <h3 class="text-lg font-semibold">Créneaux disponibles :</h3>

    @if($availableSlots)
        <div class="grid grid-cols-3 gap-2">
            @foreach($availableSlots as $slot => $isAvailable)
                <button wire:click="selectSlot('{{ $slot }}')"
                        class="p-2 rounded-lg w-full text-center font-semibold
                {{ $isAvailable ? 'bg-green-500 text-white hover:bg-green-600' : 'bg-gray-400 text-gray-700 cursor-not-allowed opacity-50' }}"
                    {{ $isAvailable ? '' : 'disabled' }}>
                    {{ $slot }}
                    @if(!$isAvailable) 🔒 @endif
                </button>
            @endforeach
        </div>
    @else
        <p class="mt-3 text-red-500">Aucun créneau disponible pour cette date.</p>
    @endif
</div>
