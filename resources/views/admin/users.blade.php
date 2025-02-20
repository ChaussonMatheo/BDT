<x-app-layout>
    <div class="flex flex-col min-h-screen bg-gray-100 p-6">
        <x-page-title title="Gérer les utilisateurs" breadcrumb="Utilisateurs" />

        <!-- Barre d'outils -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <!-- Recherche -->
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 w-full">
                <label class="font-semibold text-gray-700">Rechercher :</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher un utilisateur..." class="input input-bordered w-full md:w-64 lg:w-80">
                <button type="submit" class="rounded">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Tri -->
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 w-full">
                <label class="font-semibold text-gray-700">Trier par :</label>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.users', ['sort' => 'role', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                       class="btn btn-outline">
                        Rôle
                        @if(request('sort') === 'role')
                            @if(request('order') === 'asc') 🔼 @else 🔽 @endif
                        @endif
                    </a>
                    <a href="{{ route('admin.users', ['sort' => 'created_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                       class="btn btn-outline">
                        Date d'inscription
                        @if(request('sort') === 'created_at')
                            @if(request('order') === 'asc') 🔼 @else 🔽 @endif
                        @endif
                    </a>
                </div>
            </div>

            <!-- Bouton Ajouter -->
            <div class="flex justify-end w-full md:w-auto">
                <a href="{{ route('admin.createUser') }}" class="btn btn-success flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Ajouter
                </a>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($users as $user)
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex items-center space-x-4">
                            <div class="text-4xl">
                                @if ($user->role === 'admin')
                                    <i class="fas fa-user-shield text-red-500"></i>
                                @else
                                    <i class="fas fa-user text-blue-500"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                @if ($user->role === 'admin')
                                    <span class="badge badge-error">Admin</span>
                                @else
                                    <span class="badge badge-primary">Utilisateur</span>
                                @endif
                            </div>
                        </div>

                        <!-- Sélecteur de rôle -->
                        <form method="POST" action="{{ route('admin.updateRole', $user) }}">
                            @csrf
                            @method('PATCH')
                            <label class="text-sm font-medium text-gray-600">Rôle :</label>
                            <select name="role" class="select select-bordered w-full mt-1" onchange="this.form.submit()">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>

                        <!-- Bouton de suppression -->
                        <form method="POST" action="{{ route('admin.destroyUser', $user) }}" class="mt-4"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error w-full">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 col-span-3 text-center">Aucun utilisateur ne correspond à la recherche.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
