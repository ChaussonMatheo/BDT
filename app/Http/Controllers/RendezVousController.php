<?php
namespace App\Http\Controllers;

use App\Mail\RendezVousConfirmation;
use App\Models\RendezVous;
use App\Models\Garage;
use App\Models\Prestation;
use App\Models\Event;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\UpdateStatutMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificationRendezVousAdmin;


class RendezVousController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $filtre = $request->get('filtre', 'upcoming'); // par défaut, 'à venir'
        $statut = $request->get('statut');
        $search = $request->get('search');
        $sort = $request->get('sort', 'date_heure');

        $query = RendezVous::with(['user', 'prestation']);

        if ($filtre === 'upcoming') {
            $query->where('date_heure', '>=', $now);
        } elseif ($filtre === 'past') {
            $query->where('date_heure', '<', $now);
        }

        if ($statut && $statut !== 'all') {
            $query->where('statut', $statut);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhere('guest_name', 'like', "%{$search}%");
            });
        }

        $rendezVous = $query->orderBy($sort)->get();

        return view('rendezvous.index', compact('rendezVous', 'filtre', 'statut', 'search', 'sort'));
    }



    public function create()
    {
        $garages = Garage::all();
        $prestations = Prestation::all();
        return view('rendezvous.create', compact('garages', 'prestations'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_heure' => 'required|date',
            'garage_id' => 'nullable|exists:garages,id',
            'prestation_id' => 'nullable|exists:prestations,id',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'statut' => 'required|in:en attente,confirmé,annulé',
        ]);

        $user = Auth::user();

        $rendezVous = RendezVous::create([
            'user_id' => $user ? $user->id : null,
            'guest_name' => $user ? null : $request->guest_name,
            'guest_email' => $user ? null : $request->guest_email,
            'guest_phone' => $user ? null : $request->guest_phone,
            'garage_id' => $request->garage_id,
            'prestation_id' => $request->prestation_id,
            'date_heure' => $request->date_heure,
            'statut' => $request->statut,
        ]);

        $prestation = Prestation::findOrFail($validated['prestation_id']);
        $duree = $prestation->duree;

        $dateDebut = Carbon::parse($validated['date_heure']);
        $dateFin = $dateDebut->copy()->addMinutes($duree);

        Event::create([
            'title' => "Rendez-vous: " . $prestation->nom,
            'start_time' => $dateDebut,
            'end_time' => $dateFin,
        ]);

        $client = $user->name ?? $request->guest_name ?? 'Client invité';
        $date = $dateDebut->format('d/m/Y H:i');

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous créé avec succès.');
    }

    public function edit(RendezVous $rendezVous)
    {
        $prestations = Prestation::all();

        // Convertir date_heure en objet Carbon pour éviter l'erreur
        $rendezVous->date_heure = Carbon::parse($rendezVous->date_heure);

        // Génération des 14 prochains jours
        $availableDays = [];
        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::now()->addDays($i);
            $availableDays[] = [
                'formatted' => $date->translatedFormat('l d F'),
                'value' => $date->toDateString(),
            ];
        }

        // Créneaux horaires disponibles
        $timeSlots = [];
        $startTime = Carbon::createFromTime(9, 0);
        $endTime = Carbon::createFromTime(17, 0);
        while ($startTime < $endTime) {
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }

        return view('rendezvous.edit', compact('rendezVous', 'prestations', 'availableDays', 'timeSlots'));
    }

    public function update(Request $request, RendezVous $rendezVous)
    {
        $request->validate([
            'prestation_id' => 'required|exists:prestations,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $rendezVous->update([
            'prestation_id' => $request->prestation_id,
            'date_heure' => Carbon::parse($request->date . ' ' . $request->time),
            'statut' => 'en attente',
        ]);

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous mis à jour avec succès.');
    }
    public function destroy($id)
    {
        $rendezVous = RendezVous::findOrFail($id); // Recherche le rendez-vous

        $rendezVous->delete(); // Supprime le rendez-vous

        return redirect()->route('rendezvous.index')->with('success', 'Le rendez-vous a été supprimé avec succès.');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en attente,confirmé,annulé,refusé',
        ]);

        $rendezVous = RendezVous::findOrFail($id);
        $oldStatus = $rendezVous->statut;
        $rendezVous->statut = $request->statut;
        $rendezVous->save();

        // Envoi d'un email si un utilisateur est lié au rendez-vous
        $email = $rendezVous->user ? $rendezVous->user->email : $rendezVous->guest_email;
        $message = "Aucun email trouvé pour envoyer la notification.";

        if ($email) {
            try {
                Mail::to($email)->send(new UpdateStatutMail($rendezVous, $oldStatus));
                $message = "Un email a été envoyé au client à l'adresse $email.";
            } catch (\Exception $e) {
                $message = "Erreur lors de l'envoi de l'email : " . $e->getMessage();
            }
        }

        // Stocke le message dans la session pour déclencher un toast
        session()->flash('success', 'Le statut du rendez-vous a été modifié.');

        // Retourne une réponse JSON compatible avec AJAX
        return response()->json([
            'success' => true,
            'statut' => $rendezVous->statut
        ]);
    }


    public function showwithid($id)
    {
        // Récupérer le rendez-vous avec les relations nécessaires
        $rendezVous = RendezVous::with('prestation', 'user')->findOrFail($id);

        return response()->json([
            'id' => $rendezVous->id,
            'date_heure' => \Carbon\Carbon::parse($rendezVous->date_heure)->format('d/m/Y H:i'),
            'prestation' => [
                'service' => $rendezVous->prestation->service ?? 'Non spécifié'
            ],
            'type_de_voiture' => $rendezVous->type_de_voiture ?? 'Non spécifié',
            'tarif' => $rendezVous->tarif ?? 'Non renseigné',
            'statut' => ucfirst($rendezVous->statut),
        ]);
    }

    public function show($token)
    {
        $rendezVous = RendezVous::where('token', $token)->firstOrFail();

        return view('rendezvous.manage', compact('rendezVous'));
    }
    public function downloadICS($token)
    {
        // Récupérer le rendez-vous depuis la base de données
        $rendezVous = RendezVous::where('token', $token)->with('prestation')->firstOrFail();

        // Vérifier si la prestation existe
        if (!$rendezVous->prestation) {
            abort(404, "Prestation non trouvée pour ce rendez-vous.");
        }

        // Définir les informations pour le fichier ICS
        $eventTitle = "Rendez-vous - " . $rendezVous->prestation->service;

        // Formater la description avec des retours à la ligne corrects pour iCalendar
        $eventDescription = "Type de voiture : " . $rendezVous->type_de_voiture . "\\n";
        $eventDescription .= "Tarif : " . number_format($rendezVous->tarif, 2, ',', ' ') . "€\\n";

        // Définition du lieu (si garage_id est null, afficher 'Lieu à confirmer')
        $location = $rendezVous->garage_id ? "Garage ID : " . $rendezVous->garage_id : "Lieu à confirmer";

        // Récupération de la durée estimée depuis la prestation (en minutes)
        $dureeEstimee = intval($rendezVous->prestation->duree_estimee);
        $startTime = $rendezVous->date_heure;
        $endTime = date('Y-m-d H:i:s', strtotime($startTime . " +$dureeEstimee minutes"));

        // Générer le contenu du fichier ICS
        $icsContent = "BEGIN:VCALENDAR\r\n";
        $icsContent .= "VERSION:2.0\r\n";
        $icsContent .= "PRODID:-//BDT//FR\r\n";
        $icsContent .= "BEGIN:VEVENT\r\n";
        $icsContent .= "UID:" . uniqid() . "@bdt.com\r\n";
        $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
        $icsContent .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($startTime)) . "\r\n";
        $icsContent .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($endTime)) . "\r\n";
        $icsContent .= "SUMMARY:" . addslashes($eventTitle) . "\r\n";
        $icsContent .= "DESCRIPTION:" . addslashes($eventDescription) . "\r\n";
        $icsContent .= "LOCATION:" . addslashes($location) . "\r\n";
        $icsContent .= "END:VEVENT\r\n";
        $icsContent .= "END:VCALENDAR\r\n";

        // Retourner le fichier à télécharger
        return response($icsContent)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename=\"rendezvous.ics\""');
    }

    public function apiEvents()
    {
        $rdvs = RendezVous::with(['user', 'prestation'])->get();

        return response()->json($rdvs->map(function ($rdv) {
            $client = $rdv->user->name ?? $rdv->guest_name ?? 'Client invité';
            $voiture = match ($rdv->type_de_voiture) {
                'petite_voiture' => 'Petite voiture',
                'berline' => 'Berline',
                'suv_4x4' => 'SUV / 4x4',
                default => 'Non précisée',
            };

            $statut = ucfirst($rdv->statut ?? 'en attente');

            // Couleurs pastel
            [$bgColor, $borderColor] = match (strtolower($rdv->statut)) {
                'confirmé'   => ['#bbf7d0', '#22c55e'],
                'annulé'     => ['#fecaca', '#ef4444'],
                'refusé'     => ['#e5e7eb', '#6b7280'],
                default      => ['#dbeafe', '#3b82f6'],
            };

            return [
                'id' => $rdv->id,
                'title' => $client,
                'start' => $rdv->date_heure,
                'end' => \Carbon\Carbon::parse($rdv->date_heure)->addMinutes(45)->toDateTimeString(),
                'backgroundColor' => $bgColor,
                'borderColor' => $borderColor,
                'extendedProps' => [
                    'type' => 'rdv',
                    'client' => $client,
                    'email' => $rdv->user->email ?? $rdv->guest_email ?? 'Non renseigné',
                    'telephone' => $rdv->user->phone ?? $rdv->guest_phone ?? 'Non renseigné',
                    'prestation' => $rdv->prestation->service ?? 'Non spécifiée',
                    'tarif' => $rdv->tarif ? number_format($rdv->tarif, 2, ',', ' ') . ' €' : 'Non renseigné',
                    'voiture' => $voiture,
                    'statut' => $statut,
                    'color' => $bgColor
                ]
            ];
        }));
    }
    public function apiShow($id)
    {
        $rdv = RendezVous::with(['prestation', 'user'])->findOrFail($id);

        $typeVoiture = match ($rdv->type_de_voiture) {
            'petite_voiture' => 'Petite voiture',
            'berline' => 'Berline',
            'suv_4x4' => 'SUV / 4x4',
            default => 'Non précisée',
        };

        return response()->json([
            'id' => $rdv->id,
            'date_heure' => \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i'),
            'statut' => ucfirst($rdv->statut ?? 'en attente'),
            'client' => $rdv->user->name ?? $rdv->guest_name ?? 'Client invité',
            'email' => $rdv->user->email ?? $rdv->guest_email ?? 'Non renseigné',
            'telephone' => $rdv->user->phone ?? $rdv->guest_phone ?? 'Non renseigné',
            'prestation' => $rdv->prestation->service ?? 'Non spécifiée',
            'tarif' => $rdv->tarif ? number_format($rdv->tarif, 2, ',', ' ') . ' €' : 'Non renseigné',
            'voiture' => $typeVoiture,
        ]);
    }





}
