<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class GitHubController extends Controller
{
    public function show()
    {
        $owner = 'legrandyuna'; // Remplace par ton pseudo GitHub
        $repo = 'Plan-Eat'; // Remplace par le nom de ton repo

        $response = Http::withOptions([
            'verify' => false,
        ])->get("https://api.github.com/repos/ChaussonMatheo/BDT");

        if ($response->failed()) {
            return abort(404, "Le dépôt GitHub n'a pas été trouvé.");
        }

        $data = $response->json();

        return view('github.info', compact('data'));
    }
    public function footerInfo()
    {
        $owner = 'ChaussonMatheo'; // Remplace par ton pseudo GitHub
        $repo = 'BDT'; // Remplace par le nom du repo

        // Récupérer les infos du repo (date de mise à jour)
        $repoData = Http::get("https://api.github.com/repos/{$owner}/{$repo}")->json();

        // Récupérer le dernier commit (branche main)
        $commitData = Http::get("https://api.github.com/repos/{$owner}/{$repo}/commits/main")->json();

        // Stocker les infos
        $data = [
            'last_updated' => \Carbon\Carbon::parse($repoData['updated_at'])->diffForHumans(),
            'last_commit_message' => $commitData['commit']['message'] ?? 'Aucun commit trouvé',
            'last_commit_url' => $commitData['html_url'] ?? '#',
        ];

        return view('partials.footer', compact('data'));
    }
}
