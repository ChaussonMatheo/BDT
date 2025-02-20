<footer class="bg-gray-900 text-white text-center py-4">
    <p class="text-sm">
        📅 Dernière mise à jour :
        <strong>
            @if (!empty($data['last_updated']))
                {{ \Carbon\Carbon::parse($data['last_updated'])->locale('fr')->diffForHumans() }}
            @else
                Non disponible
            @endif
        </strong>
        |
        📝 Dernier commit :
        <a href="{{ $data['last_commit_url'] }}" class="text-blue-400 underline">
            {{ $data['last_commit_message'] }}
        </a>
    </p>
</footer>
