
<footer class="bg-gray-900 text-white text-center py-4">
    <p class="text-sm">
📅 Dernière mise à jour : <strong>{{ $data['last_updated'] }}</strong> |
📝 Dernier commit : <a href="{{ $data['last_commit_url'] }}" class="text-blue-400 underline">
    {{ $data['last_commit_message'] }}
</a>
</p>
</footer>
