<x-app-layout>

    <div class="max-w-6xl mx-auto px-4 py-10 text-white">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-primary">📂 Liens de dépôt</h1>
            <a href="{{ route('admin.uploads.create') }}" class="btn btn-accent">➕ Nouveau lien</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6">
            @forelse($uploads as $upload)
                <div class="card bg-base-200 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-sm text-gray-400">UUID</h2>
                        <a href="{{ url('/upload/' . $upload->uuid) }}" class="link link-primary break-all">
                            {{ url('/upload/' . $upload->uuid) }}
                        </a>

                        <div class="flex items-center justify-between mt-4">
                            <span class="badge badge-outline text-xs text-primary">Images : {{ $upload->images_count }}</span>

                            <div class="flex gap-2">
                                <a href="{{ route('admin.uploads.show', $upload) }}" class="btn btn-sm btn-info">
                                    👁️ Voir
                                </a>
                                <form action="{{ route('admin.uploads.destroy', $upload) }}" method="POST" onsubmit="return confirm('Supprimer ce lien ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-error">🗑️ Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-gray-400">Aucun lien de dépôt pour le moment.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
