<x-app-layout>
    <div class="flex flex-col min-h-screen bg-gray-100 p-6">

        <!-- Header -->
        <x-page-title title="Tableau de Bord" breadcrumb="Tableau de Bord" />


        <!-- Stats Cards -->
        <div class="stats shadow w-full grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <i class="fas fa-calendar-check text-4xl"></i>
                </div>
                <div class="stat-title">Total Rendez-vous</div>
                <div class="stat-value text-primary">{{ $totalRendezVous }}</div>
                <div class="stat-desc">Tous les rendez-vous enregistrés</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-green-500">
                    <i class="fas fa-check-circle text-4xl"></i>
                </div>
                <div class="stat-title">Confirmés</div>
                <div class="stat-value text-green-500">{{ $confirmedRendezVous }}</div>
                <div class="stat-desc">Rendez-vous validés</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-red-500">
                    <i class="fas fa-times-circle text-4xl"></i>
                </div>
                <div class="stat-title">Annulés</div>
                <div class="stat-value text-red-500">{{ $cancelledRendezVous }}</div>
                <div class="stat-desc">Rendez-vous supprimés</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-yellow-500">
                    <i class="fas fa-calendar-week text-4xl"></i>
                </div>
                <div class="stat-title">Cette semaine</div>
                <div class="stat-value text-yellow-500">{{ $rendezVousLastWeek }}</div>
                <div class="stat-desc">RDV programmés cette semaine</div>
            </div>
        </div>

        <!-- Graphique d'évolution -->
        <div class="card bg-base-100  p-6 mt-8">
            <h3 class="text-xl font-semibold flex items-center">
                 Évolution des rendez-vous
            </h3>
        </div>

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('rendezVousChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($rendezVousParJour->pluck('date')),
                datasets: [{
                    label: 'Nombre de rendez-vous',
                    data: @json($rendezVousParJour->pluck('count')),
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Nombre' }, beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>
