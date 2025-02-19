<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
        <h1 class="text-2xl font-semibold text-center mb-4 flex items-center justify-center gap-2">
            <i class="fas fa-calendar-check text-primary text-3xl"></i>
            Mon Rendez-vous
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Détails du rendez-vous -->
            <div class="card bg-base-100 shadow-md p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    Détails du rendez-vous
                </h2>
                <p class="text-gray-600"><strong>Date :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y - H:i') }}</p>
                <p class="text-gray-600"><strong>Service :</strong> {{ $rendezVous->prestation->service }}</p>
                <p class="text-gray-600"><strong>Statut :</strong>
                    <span class="badge {{ $rendezVous->statut == 'confirmé' ? 'badge-success' : ($rendezVous->statut == 'annulé' ? 'badge-error' : 'badge-warning') }}">
                        {{ ucfirst($rendezVous->statut) }}
                    </span>
                </p>
            </div>

            <!-- Informations client -->
            <div class="card bg-base-100 shadow-md p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-user text-green-500 text-xl"></i>
                    Informations Client
                </h2>
                <p class="text-gray-600"><strong>Nom :</strong> {{ $rendezVous->guest_name ?? $rendezVous->user->name }}</p>
                <p class="text-gray-600"><strong>Email :</strong> {{ $rendezVous->guest_email ?? $rendezVous->user->email }}</p>
                <p class="text-gray-600"><strong>Téléphone :</strong> {{ $rendezVous->guest_phone ?? 'Non renseigné' }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>

            @if($rendezVous->statut != 'annulé')
                <form action="{{ route('rendezvous.updateStatus', $rendezVous->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="statut" value="annulé">
                    <button type="submit" class="btn btn-error flex items-center gap-2">
                        <i class="fas fa-ban"></i>
                        Annuler le rendez-vous
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
