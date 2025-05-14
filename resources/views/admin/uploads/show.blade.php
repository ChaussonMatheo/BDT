<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-10 text-white">
        <h2 class="text-2xl font-bold mb-4 text-primary">Images pour : {{ $upload->uuid }}</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($upload->images as $image)
                <div class="bg-base-200 p-2 rounded shadow">
                    <img src="{{ asset('storage/' . $image->path) }}" class="rounded w-full object-cover">
                </div>
            @empty
                <p class="col-span-full text-gray-400">Aucune image déposée.</p>
            @endforelse
        </div>

        <a href="{{ route('admin.uploads.index') }}" class="btn mt-6">← Retour</a>
    </div>
</x-app-layout>
