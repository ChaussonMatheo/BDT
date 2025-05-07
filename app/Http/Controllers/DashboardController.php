<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\GarageReservationPrestation;

class DashboardController extends Controller
{


    public function index()
    {
        // Statistiques des rendez-vous
        $totalRendezVous = RendezVous::count();
        $confirmedRendezVous = RendezVous::where('statut', 'confirmé')->count();
        $cancelledRendezVous = RendezVous::where('statut', 'annulé')->count();
        $rendezVousLastWeek = RendezVous::where('date_heure', '>=', Carbon::now()->subDays(7))->count();

        // Données pour le graphique des rendez-vous par jour
        $rendezVousParJour = RendezVous::selectRaw('DATE(date_heure) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        $revenusParJour = collect();
        $prestationsParJour = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();

            $revenu = GarageReservationPrestation::whereHas('reservation', function ($query) use ($date) {
                $query->whereDate('start_date', $date);
            })->sum('montant');

            // Nombre de prestations
            $count = GarageReservationPrestation::whereHas('reservation', function ($query) use ($date) {
                $query->whereDate('start_date', $date);
            })->count();

            $prestationsParJour->push($count);
            $revenusParJour->push($revenu);
        }

        $revenuTotal = $revenusParJour->sum();
        $totalPrestations = $prestationsParJour->sum();

        return view('dashboard', compact(
            'totalRendezVous',
            'confirmedRendezVous',
            'cancelledRendezVous',
            'rendezVousLastWeek',
            'rendezVousParJour',
            'revenusParJour',
            'revenuTotal',
            'prestationsParJour',
            'totalPrestations'
        ));
    }
}
