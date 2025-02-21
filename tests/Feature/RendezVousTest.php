<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prestation;
use App\Models\RendezVous;
use App\Models\Event;
use Carbon\Carbon;

class RendezVousTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base après chaque test

    /** @test */
    public function un_rendezvous_cree_genere_un_evenement()
    {
        // Créer un utilisateur
        $user = User::factory()->create();

        // Créer une prestation avec une durée
        $prestation = Prestation::factory()->create([
            'nom' => 'Lavage complet',
            'duree' => 60, // 60 minutes
        ]);

        // Définir une date de rendez-vous
        $dateHeure = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');

        // Simuler une requête POST pour créer un rendez-vous
        $this->actingAs($user) // Simule un utilisateur connecté
        ->post(route('rendezvous.store'), [
            'date_heure' => $dateHeure,
            'prestation_id' => $prestation->id,
            'statut' => 'confirmé',
        ])
            ->assertRedirect(route('rendezvous.index')); // Vérifie la redirection

        // Vérifier que le rendez-vous a bien été créé
        $this->assertDatabaseHas('rendez_vous', [
            'date_heure' => $dateHeure,
            'prestation_id' => $prestation->id,
            'statut' => 'confirmé',
        ]);

        // Vérifier que l'événement associé a bien été créé
        $this->assertDatabaseHas('events', [
            'title' => "Rendez-vous: " . $prestation->nom,
            'start_time' => $dateHeure,
            'end_time' => Carbon::parse($dateHeure)->addMinutes($prestation->duree)->format('Y-m-d H:i:s'),
        ]);
    }
}
