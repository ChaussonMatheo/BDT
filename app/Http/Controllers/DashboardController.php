<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return view('dashboard', compact('totalRendezVous', 'confirmedRendezVous', 'cancelledRendezVous', 'rendezVousLastWeek', 'rendezVousParJour'));
    }
}
