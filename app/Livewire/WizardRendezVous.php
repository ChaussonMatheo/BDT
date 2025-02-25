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
    public $selected_service_duration = 0;
    public $selectedDate = null;
    public $selectedTime = null;
    public $timeSlots = [];
    public $availableDays = [];

    public $availableSlots = []; // Liste des créneaux disponibles

    protected $layout = 'layouts.app';


    public $selectedCarType = null;

    protected $rules = [
        'selectedCarType' => 'required|in:petite_voiture,berline,suv_4x4',
        'selectedService' => 'required|exists:prestations,id',
        'selectedDate' => 'required|date',
        'selectedTime' => 'required',
    ];

    public function mount()
    {
        $this->generateAvailableDays();
    }

    public function selectCarType($carType)
    {
        $this->selectedCarType = $carType;
        $this->resetValidation();
    }

    public function selectService($serviceId)
    {
        $this->selectedService = $serviceId;
        $prestation = Prestation::find($serviceId);
        $this->selected_service_duration = $prestation ? $prestation->duree_estimee : 0;
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

        for ($i = 0; $i < 14; $i++) { // On propose les 14 prochains jours
            $date = Carbon::now()->addDays($i);
            $dayOfWeekEn = strtolower($date->format('l')); // Jour en anglais

            // Conversion en français
            $dayOfWeekFr = $joursEnToFr[$dayOfWeekEn] ?? null;

            if (!$dayOfWeekFr) {
                continue; // Ignorer si la conversion échoue
            }

            // Vérifier si c'est un jour férié
            $isHoliday = Holiday::whereDate('date', $date)->exists();

            // Vérifier si une disponibilité existe pour ce jour
            $availabilities = Availability::where('day_of_week', $dayOfWeekFr)
                ->where('is_closed', false)
                ->exists();

            if (!$isHoliday && $availabilities) {
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
    public $blockedSlots = []; // Liste des créneaux bloqués pour le debug
    public $existingRendezVous = []; // Liste des rendez-vous existants

    public function generateTimeSlots()
    {
        $this->availableSlots = [];
        $this->blockedSlots = [];
        $this->existingRendezVous = [];

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

        $dayOfWeekFr = $joursEnToFr[$dayOfWeekEn] ?? null;
        if (!$dayOfWeekFr) return;

        // Récupérer les disponibilités pour ce jour
        $availabilities = Availability::where('day_of_week', $dayOfWeekFr)
            ->where('is_closed', false)
            ->get();

        // Récupérer tous les rendez-vous pour ce jour
        $rendezVous = RendezVous::whereDate('date_heure', $this->selectedDate)
            ->with('prestation')
            ->get();

        $this->existingRendezVous = $rendezVous->toArray(); // Stocke les rendez-vous pour affichage

        $slots = [];
        $step = 15; // Intervalle entre chaque créneau
        $blockedSlots = [];

        $pauseStart = Carbon::parse('12:00');
        $pauseEnd = Carbon::parse('14:00');
        $endOfDay = Carbon::parse('18:00');

        // **1️⃣ Bloquer la pause midi et la fin de journée**
        $currentTime = Carbon::parse('00:00');
        while ($currentTime->lt($endOfDay)) {
            $formattedTime = $currentTime->format('H:i');
            if ($currentTime->between($pauseStart, $pauseEnd, true)) {
                $blockedSlots[] = $formattedTime; // Marquer la pause
            }
            $currentTime->addMinutes($step);
        }

        // **2️⃣ Bloquer les rendez-vous existants en fonction de leur durée**
        foreach ($rendezVous as $rdv) {
            if ($rdv->prestation) {
                $rdvStart = Carbon::parse($rdv->date_heure);
                $rdvEnd = $rdvStart->copy()->addMinutes($rdv->prestation->duree_estimee);

                $tempTime = $rdvStart->copy();
                while ($tempTime->lt($rdvEnd)) {
                    $blockedSlots[] = $tempTime->format('H:i');
                    $tempTime->addMinutes($step);
                }
            }
        }

        // **3️⃣ Générer les créneaux en tenant compte des blocages**
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);

            while ($startTime->lt($endTime)) {
                $slotTime = $startTime->format('H:i');

                // Empêcher les services de dépasser la pause midi ou 18h
                $serviceEndTime = $startTime->copy()->addMinutes($this->selected_service_duration);
                if ($serviceEndTime->gt($pauseStart) && $startTime->lt($pauseStart)) {
                    $blockedSlots[] = $slotTime; // Début avant la pause
                }
                if ($serviceEndTime->gt($endOfDay)) {
                    $blockedSlots[] = $slotTime; // Dépasse 18h
                }

                $isAvailable = !in_array($slotTime, $blockedSlots);

                $slots[$slotTime] = $isAvailable;
                $startTime->addMinutes($step);
            }
        }

        $this->availableSlots = $slots;
        $this->blockedSlots = array_unique($blockedSlots);
    }




    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validateOnly('selectedCarType');
        } elseif ($this->step === 2) {
            $this->validateOnly('selectedService');
        } elseif ($this->step === 3) {
            $this->validateOnly('selectedDate');
            $this->validateOnly('selectedTime');
        }

        if ($this->step < 4) {
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
            'selectedCarType' => 'required|in:petite_voiture,berline,suv_4x4', // Ajout du type de voiture
            'guest_name' => $user ? 'nullable' : 'required|string|max:255',
            'guest_email' => $user ? 'nullable' : 'required|email|max:255',
            'guest_phone' => $user ? 'nullable' : 'required|string|max:20',
        ]);
        $prestation = Prestation::findOrFail($this->selectedService);


        // Création du rendez-vous
        $rendezVous = RendezVous::create([
            'user_id' => $user ? $user->id : null,
            'prestation_id' => $this->selectedService,
            'date_heure' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
            'guest_name' => $user ? null : $this->guest_name,
            'guest_email' => $user ? null : $this->guest_email,
            'guest_phone' => $user ? null : $this->guest_phone,
            'statut' => 'en attente',
            'tarif' => match ($this->selectedCarType) {
                'petite_voiture' => optional(Prestation::find($this->selectedService))->tarif_petite_voiture,
                'berline' => optional(Prestation::find($this->selectedService))->tarif_berline,
                'suv_4x4' => optional(Prestation::find($this->selectedService))->tarif_suv_4x4,
            },
            'type_de_voiture' => $this->selectedCarType,
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

        // Redirection vers la liste des rendez-vous
        return redirect()->route('rendezvous.confirm')->with('success', 'Votre rendez vous a bien été pris en compte. Vous allez recevoir un email de confirmation.');

    }


    public function render()
    {
        return view('livewire.wizard-rendez-vous', [
            'prestations' => Prestation::all(),
        ])->layout('layouts.app');;
    }
}
