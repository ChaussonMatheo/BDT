<x-app-layout>
    <div class="max-w-xl mx-auto py-10 px-6 text-white">
        <h2 class="text-2xl font-semibold mb-4 text-primary">Créer un nouveau lien de dépôt</h2>

        <form method="POST" action="{{ route('admin.uploads.store') }}">
            @csrf
            <button class="btn btn-primary w-full ">Générer le lien</button>
        </form>
    </div>
</x-app-layout>
