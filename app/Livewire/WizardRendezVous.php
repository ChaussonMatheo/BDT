<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Prestation;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WizardRendezVous extends Component
{
    public $step = 1;
    public $selectedService = null;
    public $selectedDate = null;
    public $selectedTime = null;
    public $timeSlots = [];
    public $availableDays = [];

    protected $rules = [
        'selectedService' => 'required|exists:prestations,id',
        'selectedDate' => 'required|date',
        'selectedTime' => 'required',
    ];

    public function mount()
    {
        $this->generateDays();
    }

    public function selectService($serviceId)
    {
        $this->selectedService = $serviceId;
        $this->resetValidation();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->generateTimeSlots();
        $this->selectedTime = null; // Réinitialisation de l'heure
    }

    public function selectTime($time)
    {
        $this->selectedTime = $time;
        $this->resetValidation();
    }

    public function generateDays()
    {
        $this->availableDays = [];
        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::now()->addDays($i);
            $this->availableDays[] = [
                'formatted' => $date->translatedFormat('l d F'),
                'value' => $date->toDateString(),
            ];
        }
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];
        $startTime = Carbon::createFromTime(9, 0);
        $endTime = Carbon::createFromTime(17, 0);

        while ($startTime < $endTime) {
            $this->timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validateOnly('selectedService');
        }

        if ($this->step === 2) {
            $this->validateOnly('selectedDate');
            $this->validateOnly('selectedTime');
        }

        if ($this->step < 3) {
            $this->step++;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function saveRendezVous()
    {
        $this->validate();

        RendezVous::create([
            'prestation_id' => $this->selectedService,
            'date_heure' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
            'statut' => 'en attente',
            'garage_id' => null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous réservé avec succès.');
    }

    public function render()
    {
        return view('livewire.wizard-rendez-vous', [
            'prestations' => Prestation::all(),
        ]);
    }
}
