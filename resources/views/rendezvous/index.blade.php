<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if(Auth::user()->role === 'admin')
            <!-- Interface pour les administrateurs -->
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Liste des Rendez-Vous
            </h2>

            <div class="flex justify-end mb-6">
                <a href="{{ route('rendezvous.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Ajouter un rendez-vous
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rendezVous as $rdv)
                    <div class="card bg-base-100 shadow-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-clock text-blue-600 mr-2"></i> {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y H:i') }}
                        </h3>

                        <p class="text-gray-600 text-sm flex items-center">
                            @if($rdv->garage)
                                <i class="fas fa-building text-purple-500 mr-2"></i> {{ $rdv->garage->nom }}
                            @elseif($rdv->prestation)
                                <i class="fas fa-car text-green-500 mr-2"></i> {{ $rdv->prestation->service }}
                            @endif
                        </p>

                        <p class="text-gray-600 text-sm flex items-center">
                            <i class="fas fa-info-circle text-gray-500 mr-2"></i> {{ ucfirst($rdv->statut) }}
                        </p>

                        <div class="mt-4 flex justify-between">
                            <a href="{{ route('rendezvous.edit', $rdv->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                            <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Supprimer ce rendez-vous ?')">
                                    <i class="fas fa-trash-alt mr-2"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Interface pour les utilisateurs classiques -->
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Mes Rendez-vous
            </h2>

            @if($rendezVous->isEmpty())
                <p class="text-gray-600 text-center">Aucun rendez-vous trouvé.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($rendezVous as $rdv)
                        <div class="card bg-base-100 shadow-lg p-4 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-wrench text-blue-600 mr-2"></i> {{ $rdv->prestation->service ?? 'Garage' }}
                            </h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-calendar text-gray-500 mr-2"></i> {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-clock text-gray-500 mr-2"></i> {{ \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') }}
                            </p>
                            <p class="text-sm font-semibold {{ $rdv->statut === 'confirmé' ? 'text-green-600' : ($rdv->statut === 'annulé' ? 'text-red-600' : 'text-yellow-600') }}">
                                <i class="fas fa-info-circle mr-1"></i> Statut : {{ ucfirst($rdv->statut) }}
                            </p>

                            <div class="flex justify-between mt-4">
                                <a href="{{ route('rendezvous.edit', $rdv->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit mr-2"></i> Modifier
                                </a>
                                <form action="{{ route('rendezvous.destroy', $rdv->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce rendez-vous ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm">
                                        <i class="fas fa-trash-alt mr-2"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
