<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Availability;
use App\Models\Appointment;
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
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);

            while ($startTime->addMinutes($this->serviceDuration)->lte($endTime)) {
                $slot = $startTime->format('H:i');

                // Vérifier si ce créneau est déjà réservé
                $isBooked = Appointment::where('date', $this->selectedDate)
                    ->where('time', $slot)
                    ->exists();

                if (!$isBooked) {
                    $slots[] = $slot;
                }
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
