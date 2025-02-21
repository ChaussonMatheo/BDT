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
use App\Mail\UpdateStatutMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;



class RendezVousController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // L'admin voit tous les rendez-vous
            $rendezVous = RendezVous::with(['user', 'prestation'])->orderBy('date_heure', 'asc')->get();
        } else {
            // Un utilisateur normal voit uniquement ses rendez-vous
            $rendezVous = RendezVous::where('user_id', Auth::id())
                ->with(['prestation'])
                ->orderBy('date_heure', 'asc')
                ->get();
        }

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

        // Créer le rendez-vous
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


        // Récupérer la durée de la prestation associée
        $prestation = Prestation::findOrFail($validated['prestation_id']);
        $duree = $prestation->duree; // Supposons que la durée est en minutes

        // Calcul de la date de fin en ajoutant la durée
        $dateDebut = Carbon::parse($validated['date_heure']);
        $dateFin = $dateDebut->addMinutes($duree);

        // Création de l'événement associé
        Event::create([
            'title' => "Rendez-vous: " . $prestation->nom,
            'start_time' => $dateDebut, // Date de début
            'end_time' => $dateFin, // Date de fin
        ]);




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
        $oldStatus = $rendezVous->statut; // Sauvegarde l'ancien statut
        $rendezVous->statut = $request->statut;
        $rendezVous->save();

        // Vérifier si le rendez-vous a un utilisateur ou un invité
        $email = $rendezVous->user ? $rendezVous->user->email : $rendezVous->guest_email;

        if ($email) {
            try {
                Mail::to($email)->send(new UpdateStatutMail($rendezVous, $oldStatus));
                $message = "Un email a été envoyé au client à l'adresse $email.";
            } catch (\Exception $e) {
                $message = "Erreur lors de l'envoi de l'email : " . $e->getMessage();
            }
        } else {
            $message = "Aucun email trouvé pour envoyer la notification.";
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès. ' . $message,
            'statut' => $rendezVous->statut // Retourne le nouveau statut
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



}
