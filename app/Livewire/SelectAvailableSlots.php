<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Availability;
use App\Models\RendezVous;
use App\Models\Holiday;

class SelectAvailableSlots extends Component
{
    public $selectedDate;
    public $serviceDuration; // Durée du service en minutes
    public $availableSlots = [];
    public $selectedSlot;

    protected $listeners = ['dateUpdated' => 'updateDate'];

    public function updateDate($date, $duration)
    {
        $this->selectedDate = $date;
        $this->serviceDuration = $duration;
        $this->fetchAvailableSlots();
    }

    public function fetchAvailableSlots()
    {
        if (!$this->selectedDate) {
            $this->availableSlots = [];
            return;
        }

        $dayOfWeek = strtolower(Carbon::parse($this->selectedDate)->format('l'));

        // Vérifier si c'est un jour férié
        if (Holiday::whereDate('date', $this->selectedDate)->exists()) {
            $this->availableSlots = [];
            return;
        }

        // Récupérer les disponibilités pour ce jour
        $availabilities = Availability::where('day_of_week', $dayOfWeek)
            ->where('is_closed', false)
            ->get();

        $slots = [];
        $step = 15; // Intervalle des créneaux en minutes
        $blockedSlots = [];

        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);

            while ($startTime->lt($endTime)) {
                $slotStart = $startTime->copy();
                $slotEnd = $slotStart->copy()->addMinutes($this->serviceDuration);

                // Vérifier si ce créneau est déjà occupé
                $isBooked = RendezVous::whereDate('date_heure', $this->selectedDate)
                    ->where(function ($query) use ($slotStart, $slotEnd) {
                        $query->whereBetween('date_heure', [$slotStart->format('Y-m-d H:i:s'), $slotEnd->format('Y-m-d H:i:s')])
                            ->orWhereRaw('? BETWEEN date_heure AND ADDTIME(date_heure, INTERVAL (SELECT prestation.duree FROM prestations WHERE prestations.id = rendez_vous.prestation_id) MINUTE)', [$slotStart->format('Y-m-d H:i:s')]);
                    })
                    ->exists();

                // Bloquer tous les créneaux affectés par une réservation
                if ($isBooked) {
                    for ($i = 0; $i < $this->serviceDuration / $step; $i++) {
                        $blockedSlots[] = $slotStart->copy()->addMinutes($i * $step)->format('H:i');
                    }
                }

                $slots[$slotStart->format('H:i')] = !in_array($slotStart->format('H:i'), $blockedSlots);
                $startTime->addMinutes($step);
            }
        }

        $this->availableSlots = $slots;
    }

    public function selectSlot($slot)
    {
        $this->selectedSlot = $slot;
        $this->emit('slotSelected', $slot);
    }

    public function render()
    {
        return view('livewire.select-available-slots');
    }
}
