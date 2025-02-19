<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Prestation;
use App\Models\RendezVous;
use App\Models\Availability;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use App\Mail\RendezVousConfirmation;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
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

    public $availableSlots = []; // Liste des créneaux disponibles

    public $serviceDuration = 30; // Durée en minutes

    protected $rules = [
        'selectedService' => 'required|exists:prestations,id',
        'selectedDate' => 'required|date',
        'selectedTime' => 'required',
    ];

    public function mount()
    {
        $this->generateAvailableDays();
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

    /**
     * Génère la liste des jours où au moins un garage est ouvert.
     */
    public function generateAvailableDays()
    {
        $this->availableDays = [];

        // Tableau de conversion EN -> FR
        $joursEnToFr = [
            'monday'    => 'lundi',
            'tuesday'   => 'mardi',
            'wednesday' => 'mercredi',
            'thursday'  => 'jeudi',
            'friday'    => 'vendredi',
            'saturday'  => 'samedi',
            'sunday'    => 'dimanche',
        ];

        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::now()->addDays($i);
            $dayOfWeekEn = strtolower($date->format('l')); // Jour en anglais

            // Conversion en français
            $dayOfWeekFr = $joursEnToFr[$dayOfWeekEn] ?? null;

            if (!$dayOfWeekFr) {
                continue; // S'il n'existe pas, on ignore
            }

            // Vérifier si c'est un jour férié
            $isHoliday = Holiday::whereDate('date', $date)->exists();

            // Vérifier si une disponibilité existe pour ce jour
            $availabilities = Availability::where('day_of_week', $dayOfWeekFr)
                ->where('is_closed', false)
                ->get();

            if (!$isHoliday && $availabilities->isNotEmpty()) {
                $this->availableDays[] = [
                    'formatted' => $date->translatedFormat('l d F'),
                    'value' => $date->toDateString(),
                ];
            }
        }
    }



    /**
     * Génère les créneaux horaires disponibles en fonction des disponibilités et des rendez-vous existants.
     */
    public function generateTimeSlots()
    {
        $this->availableSlots = []; // Réinitialisation
        if (!$this->selectedDate) return;

        $dayOfWeekEn = strtolower(Carbon::parse($this->selectedDate)->format('l'));

        // Tableau de conversion EN -> FR
        $joursEnToFr = [
            'monday'    => 'lundi',
            'tuesday'   => 'mardi',
            'wednesday' => 'mercredi',
            'thursday'  => 'jeudi',
            'friday'    => 'vendredi',
            'saturday'  => 'samedi',
            'sunday'    => 'dimanche',
        ];

        // Convertir en français
        $dayOfWeekFr = $joursEnToFr[$dayOfWeekEn] ?? null;
        if (!$dayOfWeekFr) {
            return;
        }

        // Récupérer les disponibilités pour ce jour
        $availabilities = Availability::where('day_of_week', $dayOfWeekFr)
            ->where('is_closed', false)
            ->get();

        $slots = [];
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);

            while ($startTime->addMinutes($this->serviceDuration)->lte($endTime)) {
                $slot = $startTime->format('H:i');

                // Vérifier si ce créneau est déjà réservé
                $isBooked = RendezVous::where('date_heure', $this->selectedDate . ' ' . $slot)
                    ->exists();

                if (!$isBooked) {
                    $slots[] = $slot;
                }
            }
        }

        $this->availableSlots = $slots;

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

    public $guest_name;
    public $guest_email;
    public $guest_phone;

    public function saveRendezVous()
    {
        $user = Auth::user();

        // Validation des champs
        $this->validate([
            'selectedService' => 'required|exists:prestations,id',
            'selectedDate' => 'required|date',
            'selectedTime' => 'required',
            'guest_name' => $user ? 'nullable' : 'required|string|max:255',
            'guest_email' => $user ? 'nullable' : 'required|email|max:255',
            'guest_phone' => $user ? 'nullable' : 'required|string|max:20',
        ]);

        // Création du rendez-vous
        $rendezVous = RendezVous::create([
            'user_id' => $user ? $user->id : null,
            'prestation_id' => $this->selectedService,
            'date_heure' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
            'guest_name' => $user ? null : $this->guest_name,
            'guest_email' => $user ? null : $this->guest_email,
            'guest_phone' => $user ? null : $this->guest_phone,
            'statut' => 'en attente',
        ]);

        // Envoi de l'email de confirmation
        try {
            if ($user) {
                Mail::to($user->email)->send(new RendezVousConfirmation($rendezVous));
                $message = "Un email de confirmation a été envoyé à : " . $user->email;
            } elseif ($rendezVous->guest_email) {
                Mail::to($rendezVous->guest_email)->send(new RendezVousConfirmation($rendezVous));
                $message = "Un email de confirmation a été envoyé à : " . $rendezVous->guest_email;
            } else {
                $message = "Aucun email à envoyer.";
            }
        } catch (\Exception $e) {
            $message = "Erreur lors de l'envoi de l'email : " . $e->getMessage();
        }

        // Flash message pour l'utilisateur avec debug info
        session()->flash('success', 'Rendez-vous enregistré avec succès. ' . $message);

        // Redirection vers la liste des rendez-vous
        return redirect()->route('rendezvous.index');
    }


    public function render()
    {
        return view('livewire.wizard-rendez-vous', [
            'prestations' => Prestation::all(),
        ]);
    }
}
