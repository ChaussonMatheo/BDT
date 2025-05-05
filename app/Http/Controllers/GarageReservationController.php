<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Garage as Garage;
use App\Models\GarageReservation;
use App\Models\GarageReservationPrestation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


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
        ]);

        $reservation = GarageReservation::findOrFail($id);
        $reservation->update([
            'garage_id' => $validated['garage_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        $reservation->prestations()->delete(); // suppression simplifiée
        foreach ($validated['prestations'] as $presta) {
            $reservation->prestations()->create($presta);
        }

        return redirect()->route('garage-reservations.edit', $id)->with('success', 'Réservation mise à jour.');
    }


    public function facture($id)
    {
        $reservation = GarageReservation::with(['garage', 'prestations'])->findOrFail($id);

        $pdf = Pdf::loadView('garage_reservations.facture', compact('reservation'))
            ->setPaper('A4');

        return $pdf->download("Facture-Garage-{$reservation->id}.pdf");
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
                'backgroundColor' => '#fef08a', // jaune pale
                'borderColor' => '#ca8a04',
                'extendedProps' => [
                    'type' => 'garage',
                    'garage' => $r->garage->nom,
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




}
