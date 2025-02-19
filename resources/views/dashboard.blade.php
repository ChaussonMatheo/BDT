<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-full p-6">
            <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
            <nav class="mt-6">
                <a href="/dashboard" class="block py-2 px-4 text-blue-600 font-semibold bg-blue-100 rounded">Accueil</a>
                <a href="/rendezvous" class="block py-2 px-4 mt-2 text-gray-700 hover:bg-gray-200 rounded">Rendez-vous</a>
                <a href="/utilisateurs" class="block py-2 px-4 mt-2 text-gray-700 hover:bg-gray-200 rounded">Utilisateurs</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Statistiques</h1>
                <button class="btn btn-primary">Rafraîchir</button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold">Total Rendez-vous</h3>
                    <p class="text-2xl font-bold">{{ $totalRendezVous }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold">Confirmés</h3>
                    <p class="text-2xl font-bold">{{ $confirmedRendezVous }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold">Annulés</h3>
                    <p class="text-2xl font-bold">{{ $cancelledRendezVous }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded shadow-md">
                    <h3 class="text-lg font-semibold">Cette semaine</h3>
                    <p class="text-2xl font-bold">{{ $rendezVousLastWeek }}</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white p-6 rounded shadow-md mt-6">
                <h3 class="text-xl font-semibold">Évolution des rendez-vous</h3>
                <canvas id="rendezVousChart"></canvas>
            </div>

            <!-- Table des rendez-vous -->

        </main>
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
                    borderColor: 'blue',
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
