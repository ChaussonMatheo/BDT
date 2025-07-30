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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificationRendezVousAdmin;


class RendezVousController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RendezVous::with(['user', 'prestation']);

        // Si l'utilisateur n'est pas admin, filtrer uniquement ses rendez-vous
        if ($user->role !== 'admin') {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('guest_email', $user->email);
            });
        }

        // Récupérer tous les rendez-vous (le filtrage se fera côté front)
        $rendezVous = $query->orderBy('date_heure', 'desc')->get();

        return view('rendezvous.index', compact('rendezVous'));
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

    /**
     * Affiche le formulaire annexe pour créer un rendez-vous rapidement
     */
    public function createAnnexe()
    {
        $prestations = Prestation::all();
        $garages = Garage::all();

        // Génération des 30 prochains jours
        $availableDays = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->addDays($i);
            $availableDays[] = [
                'formatted' => $date->translatedFormat('l d F Y'),
                'value' => $date->toDateString(),
            ];
        }

        // Créneaux horaires disponibles (9h-17h)
        $timeSlots = [];
        $startTime = Carbon::createFromTime(9, 0);
        $endTime = Carbon::createFromTime(17, 0);
        while ($startTime < $endTime) {
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }

        return view('rendezvous.create-annexe', compact('prestations', 'garages', 'availableDays', 'timeSlots'));
    }

    /**
     * Enregistre un rendez-vous créé via le formulaire annexe
     */
    public function storeAnnexe(Request $request)
    {
        $validated = $request->validate([
            'prestations' => 'required|array|min:1',
            'prestations.*.description' => 'required|string|max:255',
            'prestations.*.montant' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'type_de_voiture' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        // Créer la date complète
        $dateHeure = Carbon::parse($validated['date'] . ' ' . $validated['time']);

        // Calculer le tarif total
        $tarifTotal = 0;
        foreach ($validated['prestations'] as $prestation) {
            $tarifTotal += $prestation['montant'] ?? 0;
        }

        // Créer une description combinée des prestations
        $prestationsDescriptions = array_column($validated['prestations'], 'description');
        $prestationLibre = implode(' + ', $prestationsDescriptions);

        // Créer le rendez-vous
        $rendezVous = RendezVous::create([
            'prestation_id' => null, // Pas de prestation prédéfinie
            'garage_id' => null, // Pas de garage
            'date_heure' => $dateHeure,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'type_de_voiture' => $validated['type_de_voiture'],
            'tarif' => $tarifTotal,
            'statut' => 'confirmé',
            'notes' => $validated['notes'] ?? null,
            'prestation_libre' => $prestationLibre, // Description combinée
        ]);

        // Créer l'événement dans le calendrier seulement si c'est futur
        if ($dateHeure->isFuture()) {
            Event::create([
                'title' => $prestationLibre . ' - ' . $validated['guest_name'],
                'start' => $dateHeure,
                'end' => $dateHeure->copy()->addHours(1),
                'color' => '#10b981',
            ]);
        }

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous créé avec succès via le formulaire annexe.');
    }

    /**
     * Génère une facture PDF pour un rendez-vous avec prestations libres
     */
    public function facturePdf($id)
    {
        $rendezVous = RendezVous::findOrFail($id);

        // Vérifier que c'est un RDV avec prestations libres
        if (!$rendezVous->prestation_libre) {
            return redirect()->back()->with('error', 'Ce rendez-vous ne dispose pas de prestations libres.');
        }

        // Récupération des paramètres légaux
        $legal_emetteur = \App\Models\Setting::getValue('legal_emetteur', 'ND');
        $legal_siret = \App\Models\Setting::getValue('legal_siret', 'ND');
        $legal_iban = \App\Models\Setting::getValue('legal_iban', 'ND');

        // Séparer les prestations par le "+"
        $prestations = array_map('trim', explode('+', $rendezVous->prestation_libre));

        $pdf = Pdf::loadView('rendezvous.facture-prestations', compact('rendezVous', 'prestations', 'legal_emetteur', 'legal_siret', 'legal_iban'))
            ->setPaper('A4');
        $rendezVousName = $rendezVous->guest_name ?: $rendezVous->user->name ?? 'Client invité';
        return $pdf->stream("Facture-RDV-{$rendezVousName}.pdf");
    }

    /**
     * Envoie une facture par email pour un rendez-vous avec prestations libres
     */
    public function envoyerFactureEmail(Request $request)
    {
        $request->validate([
            'rdv_id' => 'required|exists:rendez_vous,id',
            'email_destinataire' => 'required|email'
        ]);

        $rendezVous = RendezVous::findOrFail($request->rdv_id);

        // Vérifier que c'est un RDV avec prestations libres
        if (!$rendezVous->prestation_libre) {
            return response()->json([
                'success' => false,
                'message' => 'Ce rendez-vous ne dispose pas de prestations libres.'
            ]);
        }

        try {
            // Récupération des paramètres légaux
            $legal_emetteur = \App\Models\Setting::getValue('legal_emetteur', 'B-CLEAN - 123 rue du Soin Auto, 75000 Paris');
            $legal_siret = \App\Models\Setting::getValue('legal_siret', '123 456 789 00021');
            $legal_iban = \App\Models\Setting::getValue('legal_iban', 'FR76 3000 6000 0112 3456 7890 189');

            // Séparer les prestations par le "+"
            $prestations = array_map('trim', explode('+', $rendezVous->prestation_libre));

            // Génération du PDF
            $pdf = Pdf::loadView('rendezvous.facture-prestations', compact('rendezVous', 'prestations', 'legal_emetteur', 'legal_siret', 'legal_iban'))
                ->setPaper('A4');

            $pdfContent = $pdf->output();

            // Envoi de l'email
            Mail::to($request->email_destinataire)->send(new \App\Mail\FactureRendezVousMail($rendezVous, $pdfContent));

            return response()->json([
                'success' => true,
                'message' => 'Facture envoyée avec succès à ' . $request->email_destinataire
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ]);
        }
    }

}
