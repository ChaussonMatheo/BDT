<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <x-page-title title="Galerie photos" breadcrumb="Images" />

        <!-- Boutons d'action -->
        <div class="flex flex-col md:flex-row justify-end gap-4 mt-6">
            <a href="{{ route('admin.uploads.create') }}" class="btn btn-accent">
                ➕ Générer un lien de dépôt
            </a>
            <a href="{{ route('admin.uploads.index') }}" class="btn btn-outline">
                📂 Consulter les liens
            </a>
        </div>

        <!-- Galerie -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
            @forelse ($images as $image)
                <div class="card bg-base-100 shadow-lg">
                    <figure>
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Image" class="object-cover w-full h-48 rounded-t-lg" />
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title text-sm truncate">{{ $image->name ?? 'Image' }}</h2>

                        @if ($image->home_position)
                            <p class="text-xs text-green-600">Affiché en position #{{ $image->home_position }} sur la page d'accueil</p>
                        @endif

                        <div class="flex justify-between items-center mt-2 gap-2">
                            <a href="{{ asset('storage/' . $image->path) }}" data-lightbox="gallery" class="btn btn-sm btn-primary">Voir</a>

                            <form action="{{ route('admin.uploads.destroyImage', $image) }}" method="POST" onsubmit="return confirm('Supprimer cette image ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-error">🗑️</button>
                            </form>

                            <!-- Bouton pour ouvrir le modal -->
                            <label for="modal-{{ $image->id }}" class="btn btn-sm btn-outline">...</label>
                        </div>
                    </div>
                </div>

                <!-- Modal par image -->
                <input type="checkbox" id="modal-{{ $image->id }}" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box max-w-3xl w-full">
                        <h3 class="font-bold text-lg">Définir la position d'affichage sur la page d'accueil</h3>

                        <!-- Aperçu visuel avec diff DaisyUI -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            @for ($i = 1; $i <= 6; $i += 2)
                                <figure class="diff aspect-16/9" tabindex="0">
                                    <div class="diff-item-1" role="img" tabindex="0">
                                        <div class="bg-primary text-primary-content grid place-content-end text-xl font-black">
                                            {{ $i + 1}}
                                        </div>
                                    </div>
                                    <div class="diff-item-2" role="img">
                                        <div class="bg-base-200 grid place-content-start text-xl font-black">{{ $i }}</div>
                                    </div>
                                    <div class="diff-resizer"></div>
                                </figure>
                            @endfor
                        </div>

                        <!-- Formulaire pour définir la position -->
                        <form action="{{ route('admin.uploads.setHomePosition', $image) }}" method="POST" class="mt-6">
                            @csrf
                            <label class="label font-semibold">Choisir une position (1 à 6)</label>
                            <input type="number" name="home_position" min="1" max="6" value="{{ $image->home_position }}" class="input input-bordered w-full" required>

                            <div class="modal-action">
                                <button type="submit" class="btn btn-primary">Valider</button>
                                <label for="modal-{{ $image->id }}" class="btn">Annuler</label>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-400">
                    Aucune image disponible pour le moment.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
