<x-app-layout>

    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $data['name'] }}</h1>
            <p class="text-gray-600 mt-2">{{ $data['description'] }}</p>

            <div class="mt-4">
                <strong>📅 Créé le :</strong> {{ \Carbon\Carbon::parse($data['created_at'])->format('d/m/Y') }} <br>
                <strong>🔄 Dernière mise à jour :</strong> {{ \Carbon\Carbon::parse($data['updated_at'])->diffForHumans() }} <br>
                <strong>⭐ Stars :</strong> {{ $data['stargazers_count'] }} <br>
                <strong>🍴 Forks :</strong> {{ $data['forks_count'] }} <br>
                <strong>📂 Langage principal :</strong> {{ $data['language'] }} <br>
                <strong>👤 Auteur :</strong> <a href="{{ $data['owner']['html_url'] }}" class="text-blue-500">{{ $data['owner']['login'] }}</a> <br>
            </div>

            <a href="{{ $data['html_url'] }}" class="btn btn-primary mt-4" target="_blank">Voir sur GitHub</a>
        </div>
    </div>
</x-app-layout>
