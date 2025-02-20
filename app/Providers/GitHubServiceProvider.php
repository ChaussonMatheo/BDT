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
            $owner = 'ChaussonMatheo'; // Ton pseudo GitHub
            $repo = 'BDT'; // Ton repo GitHub

            try {
                // Désactiver la vérification SSL pour éviter l'erreur cURL 60
                $repoData = Http::withOptions(['verify' => false])
                    ->get("https://api.github.com/repos/{$owner}/{$repo}")
                    ->json();

                $commitData = Http::withOptions(['verify' => false])
                    ->get("https://api.github.com/repos/{$owner}/{$repo}/commits/main")
                    ->json();

                $data = [
                    'last_updated' => Carbon::parse($repoData['updated_at'])->diffForHumans(),
                    'last_commit_message' => $commitData['commit']['message'] ?? 'Aucun commit trouvé',
                    'last_commit_url' => $commitData['html_url'] ?? '#',
                ];
            } catch (\Exception $e) {
                $data = [
                    'last_updated' => 'Inconnu',
                    'last_commit_message' => 'Erreur de récupération des données',
                    'last_commit_url' => '#',
                ];
            }

            $view->with('data', $data);
        });
    }
}
