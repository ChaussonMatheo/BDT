<?php
namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Garage;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RendezVousController extends Controller
{
    public function index()
    {
        $rendezVous = RendezVous::orderBy('date_heure', 'asc')->get();
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
        $request->validate([
            'date_heure' => 'required|date',
            'garage_id' => 'nullable|exists:garages,id',
            'prestation_id' => 'nullable|exists:prestations,id',
            'statut' => 'required|in:en attente,confirmé,annulé',
        ]);

        // Créer le rendez-vous
        $rendezVous = RendezVous::create($request->all());

        // Générer un événement dans le planning
        event(new \App\Events\RendezVousCreated($rendezVous));

        return redirect()->route('rendezvous.index')->with('success', 'Rendez-vous ajouté avec succès.');
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
    public function destroy(RendezVous $rendezVous)
    {
        $rendezVous->delete();

        return redirect()->route('rendezvous.index')->with('success', 'Le rendez-vous a été supprimé avec succès.');
    }

}
