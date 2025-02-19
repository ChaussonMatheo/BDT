<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i> Gestion des Disponibilités
        </h2>

        <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
            <div class="flex justify-between mb-4">
                <a href="{{ route('availabilities.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle mr-2"></i> Ajouter une disponibilité
                </a>
            </div>

            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Jour</th>
                    <th class="border p-2">Heure d'ouverture</th>
                    <th class="border p-2">Heure de fermeture</th>
                    <th class="border p-2">Statut</th>
                    <th class="border p-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($availabilities as $availability)
                    <tr class="border">
                        <td class="border p-2">{{ ucfirst($availability->day_of_week) }}</td>
                        <td class="border p-2">{{ $availability->start_time }}</td>
                        <td class="border p-2">{{ $availability->end_time }}</td>
                        <td class="border p-2">
                            @if ($availability->is_closed)
                                <span class="text-red-500 font-semibold">Fermé</span>
                            @else
                                <span class="text-green-500 font-semibold">Ouvert</span>
                            @endif
                        </td>
                        <td class="border p-2 flex space-x-2">
                            <a href="{{ route('availabilities.edit', $availability) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('availabilities.destroy', $availability) }}" method="POST" onsubmit="return confirm('Supprimer cette disponibilité ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
