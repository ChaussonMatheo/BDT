<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class GitHubServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $owner = 'ChaussonMatheo'; // Remplace par ton pseudo GitHub
            $repo = 'BDT'; // Remplace par ton repo GitHub

            try {
                // Requête pour récupérer les infos du dépôt (avec désactivation SSL)
                $repoResponse = Http::withOptions(['verify' => false])
                    ->get("https://api.github.com/repos/{$owner}/{$repo}");

                $commitResponse = Http::withOptions(['verify' => false])
                    ->get("https://api.github.com/repos/{$owner}/{$repo}/commits/main");

                // Vérifier si la requête a réussi (statut HTTP 200)
                if ($repoResponse->successful() && $commitResponse->successful()) {
                    $repoData = $repoResponse->json();
                    $commitData = $commitResponse->json();

                    $data = [
                        'last_updated' => isset($repoData['updated_at']) ? Carbon::parse($repoData['updated_at'])->diffForHumans() : 'Non disponible',
                        'last_commit_message' => isset($commitData['commit']['message']) ? $commitData['commit']['message'] : 'Aucun commit trouvé',
                        'last_commit_url' => isset($commitData['html_url']) ? $commitData['html_url'] : '#',
                    ];
                } else {
                    // Si l'API retourne une erreur
                    $data = [
                        'last_updated' => 'Erreur API GitHub',
                        'last_commit_message' => 'Impossible de récupérer les commits',
                        'last_commit_url' => '#',
                    ];
                }
            } catch (\Exception $e) {
                // En cas d'erreur de connexion ou d'autres exceptions
                $data = [
                    'last_updated' => 'Inconnu',
                    'last_commit_message' => 'Erreur de récupération des données',
                    'last_commit_url' => '#',
                ];
            }

            // Passer les données à la vue
            $view->with('data', $data);
        });
    }
}
