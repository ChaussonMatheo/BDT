<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Garage as Garage;
use App\Models\GarageReservation;
use App\Models\GarageReservationPrestation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\FactureGarageMail;
use Illuminate\Support\Facades\Mail;


class GarageReservationController extends Controller
{
    public function create()
    {
        $garages = Garage::all();
        return view('garage_reservations.create', compact('garages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'garage_id' => 'required|exists:garages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'prestations' => 'required|array|min:1',
            'prestations.*.description' => 'required|string|max:255',
            'prestations.*.montant' => 'required|numeric|min:0',
        ]);

        $reservation = GarageReservation::create([
            'garage_id' => $validated['garage_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        foreach ($validated['prestations'] as $item) {
            $reservation->prestations()->create($item);
        }

        return redirect()->route('garage-reservations.create')->with('success', 'Réservation enregistrée.');
    }
    public function edit($id)
    {
        $reservation = GarageReservation::with('prestations')->findOrFail($id);
        $garages = Garage::all();

        return view('garage_reservations.edit', compact('reservation', 'garages'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'garage_id' => 'required|exists:garages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'prestations' => 'required|array|min:1',
            'prestations.*.description' => 'required|string|max:255',
            'prestations.*.montant' => 'required|numeric|min:0',
            'couleur' => 'nullable|string|max:7', // ex : "#ff0000"
        ]);

        $reservation = GarageReservation::findOrFail($id);
        $reservation->update([
            'garage_id' => $validated['garage_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'couleur' => $validated['couleur'] ?? '#2196f3', // valeur par défaut si vide
        ]);

        $reservation->prestations()->delete(); // suppression simplifiée
        foreach ($validated['prestations'] as $presta) {
            $reservation->prestations()->create($presta);
        }

        return redirect()->route('garages.index', ['garage' => $reservation->garage_id])
            ->with('success', 'Réservation mise à jour.');
    }



    public function facture($id)
    {
        $reservation = GarageReservation::with(['garage', 'prestations'])->findOrFail($id);

        // Récupération des paramètres légaux
        $legal_emetteur = \App\Models\Setting::getValue('legal_emetteur', 'B-CLEAN - 123 rue du Soin Auto, 75000 Paris');
        $legal_siret = \App\Models\Setting::getValue('legal_siret', '123 456 789 00021');
        $legal_iban = \App\Models\Setting::getValue('legal_iban', 'FR76 3000 6000 0112 3456 7890 189');

        $pdf = Pdf::loadView('garage_reservations.facture', compact('reservation', 'legal_emetteur', 'legal_siret', 'legal_iban'))
            ->setPaper('A4');
        $startDate = \Carbon\Carbon::parse($reservation->start_date)->format('Y-m-d');
        $garageName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $reservation->garage->nom);
        return $pdf->stream("Facture-{$garageName}" . $startDate .  "_" . ".pdf");
    }

    public function apiEvents()
    {
        $reservations = GarageReservation::with('garage')->get();

        return response()->json($reservations->map(function ($r) {
            return [
                'id' => 'garage-' . $r->id,
                'title' => '🔧 Garage: ' . $r->garage->nom,
                'start' => $r->start_date,
                'end' => \Carbon\Carbon::parse($r->end_date)->addDay()->toDateString(), // fullcalendar exclude end date
                'allDay' => true,
                'backgroundColor' => '#fef08a',
                'borderColor' => '#ca8a04',
                'extendedProps' => [
                    'type' => 'garage',
                    'garage' => $r->garage->nom,
                    'color' => $r->couleur ?? '#3d65c6',
                    'notes' => $r->notes,
                ]
            ];
        }));
    }
    public function apiShow($id)
    {
        $reservation = GarageReservation::with(['garage', 'prestations'])->findOrFail($id);

        return response()->json([
            'id' => $reservation->id,
            'garage' => $reservation->garage->nom,
            'lieu' => $reservation->garage->lieu,
            'start' => $reservation->start_date,
            'end' => $reservation->end_date,

            'prestations' => $reservation->prestations->map(function ($p) {
                return [
                    'description' => $p->description,
                    'montant' => number_format($p->montant, 2, ',', ' ') . ' €'
                ];
            }),
            'total' => number_format($reservation->prestations->sum('montant'), 2, ',', ' ') . ' €'
        ]);
    }

    public function envoyerFacture(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $reservation = GarageReservation::with(['garage', 'prestations'])->findOrFail($id);

        // Récupération des paramètres légaux
        $legal_emetteur = \App\Models\Setting::getValue('legal_emetteur', 'ND');
        $legal_siret = \App\Models\Setting::getValue('legal_siret', 'ND');
        $legal_iban = \App\Models\Setting::getValue('legal_iban', 'ND');

        // Génération du PDF
        $pdf = Pdf::loadView('garage_reservations.facture', compact('reservation', 'legal_emetteur', 'legal_siret', 'legal_iban'))
            ->setPaper('A4');

        $pdfContent = $pdf->output();

        // Envoi de l'email
        Mail::to($request->email)->send(new FactureGarageMail($reservation, $pdfContent));

        return back()->with('success', 'Facture envoyée avec succès à ' . $request->email);
    }




}
