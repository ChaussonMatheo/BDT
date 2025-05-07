<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Prestation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\NotificationRendezVousAdmin;

class RendezVousTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_peut_creer_un_rendezvous_et_les_admins_sont_notifies()
    {
        Mail::fake();

        // Créer un admin à notifier
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        // Créer un utilisateur connecté
        $user = User::factory()->create();
        $this->actingAs($user);

        // Créer une prestation
        $prestation = Prestation::factory()->create([
            'service' => 'Nettoyage complet',
            'duree_estimee' => 60,
            'description' => 'test',
            'tarif_petite_voiture' => 30,
            'tarif_berline' => 30,
            'tarif_suv_4x4' => 30,
        ]);

        // Créer un rendez-vous
        $response = $this->post(route('rendezvous.store'), [
            'date_heure' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'prestation_id' => $prestation->id,
            'statut' => 'confirmé',
        ]);

        // Vérifie la redirection
        $response->assertRedirect();

        // Vérifie que le rendez-vous a été créé
        $this->assertDatabaseHas('rendez_vous', [
            'user_id' => $user->id,
            'prestation_id' => $prestation->id,
            'statut' => 'confirmé',
        ]);

        // Vérifie que le mail a bien été envoyé à l'admin
        Mail::assertSent(NotificationRendezVousAdmin::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });
    }
}
