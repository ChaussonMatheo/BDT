<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-4">Ajouter un utilisateur</h2>

        <!-- Affichage des erreurs -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.storeUser') }}">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-sm">Nom</label>
                <input type="text" name="name" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm">Adresse e-mail</label>
                <input type="email" name="email" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm">Mot de passe</label>
                <input type="password" name="password" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm">Rôle</label>
                <select name="role" class="border rounded w-full p-2">
                    <option value="user">Utilisateur</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
