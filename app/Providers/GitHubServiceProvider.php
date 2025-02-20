<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GitHubServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $owner = 'ChaussonMatheo'; // Remplace par ton pseudo GitHub
            $repo = 'BDT'; // Remplace par ton repo GitHub
            $cacheKey = "github_data_{$repo}";

            // Vérifie si les données sont déjà en cache (stockées 1 heure)
            $data = Cache::remember($cacheKey, 3600, function () use ($owner, $repo) {
                try {
                    // Si un token GitHub est configuré dans .env, on l'utilise
                    $headers = [];
                    if (config('services.github.token')) {
                        $headers['Authorization'] = 'token ' . config('services.github.token');
                    }

                    // Récupération des données depuis GitHub API
                    $repoResponse = Http::withOptions(['verify' => false])
                        ->withHeaders($headers)
                        ->get("https://api.github.com/repos/{$owner}/{$repo}");

                    $commitResponse = Http::withOptions(['verify' => false])
                        ->withHeaders($headers)
                        ->get("https://api.github.com/repos/{$owner}/{$repo}/commits/main");

                    // Vérifier si la requête a réussi (statut HTTP 200)
                    if ($repoResponse->successful() && $commitResponse->successful()) {
                        $repoData = $repoResponse->json();
                        $commitData = $commitResponse->json();

                        return [
                            'last_updated' => isset($repoData['updated_at']) ? Carbon::parse($repoData['updated_at'])->diffForHumans() : 'Non disponible',
                            'last_commit_message' => $commitData['commit']['message'] ?? 'Aucun commit trouvé',
                            'last_commit_url' => $commitData['html_url'] ?? '#',
                        ];
                    } else {
                        Log::error("GitHub API Error", [
                            'repo_status' => $repoResponse->status(),
                            'commit_status' => $commitResponse->status(),
                        ]);

                        return [
                            'last_updated' => 'Erreur API GitHub',
                            'last_commit_message' => 'Impossible de récupérer les commits',
                            'last_commit_url' => '#',
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error("GitHub API Exception: " . $e->getMessage());

                    return [
                        'last_updated' => 'Inconnu',
                        'last_commit_message' => 'Erreur de récupération des données',
                        'last_commit_url' => '#',
                    ];
                }
            });

            // Passer les données à la vue
            $view->with('data', $data);
        });
    }
}
