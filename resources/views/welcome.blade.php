<x-app-layout>
    <div class="min-h-screen bg-gray-100">
        <!-- Hero Section -->
        <section class="hero min-h-screen bg-white text-emerald-600 flex items-center">
            <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6">
                <!-- Image à gauche -->
                <div class="w-full md:w-1/2 flex justify-center">
                    <img src="{{ asset('images/car.png') }}" alt="Detailing Automobile"
                         class="max-w-xs md:max-w-md rounded-lg">
                </div>

                <!-- Texte à droite -->
                <div class="w-full md:w-1/2 text-right relative">
                    <h1 class="text-4xl md:text-6xl font-bold shine-text text-emerald-500">Impeccable</h1>
                    <p class="mt-4 text-lg md:text-xl text-blue-950">
                        Offrez à votre voiture un soin d’exception.
                    </p>
                    <a href="{{ route('rendezvous.create') }}" class="btn btn-primary mt-6">
                        Prendre Rendez-vous
                    </a>
                </div>
            </div>
        </section>


        <!-- Présentation -->
        <section class="container mx-auto my-16 text-center px-6">
            <h2 class="text-4xl font-bold">Notre Expertise</h2>
            <p class="mt-4 text-lg">BDT est spécialisé dans le detailing automobile, proposant des services de nettoyage et protection haut de gamme.</p>
        </section>

        <!-- Tableau des Prix -->
        <section class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center">Nos Tarifs</h2>
            <div class="overflow-x-auto mt-6">
                <table class="table w-full bg-white shadow-md rounded-lg">
                    <thead>
                    <tr class="bg-gray-200">
                        <th>Service</th>
                        <th>Prix</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td>Nettoyage intérieur</td><td>50€</td></tr>
                    <tr><td>Polissage carrosserie</td><td>120€</td></tr>
                    <tr><td>Céramique 9H</td><td>250€</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Galerie Avant/Après -->
        <section class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center">Nos Réalisations</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <img src="/images/avant-apres1.jpg" class="rounded-lg shadow-lg" alt="Avant Après 1">
                <img src="/images/avant-apres2.jpg" class="rounded-lg shadow-lg" alt="Avant Après 2">
                <img src="/images/avant-apres3.jpg" class="rounded-lg shadow-lg" alt="Avant Après 3">
            </div>
        </section>

        <!-- Témoignages Clients -->
        <section class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center">Avis Clients</h2>
            <div class="mt-6 space-y-4">
                <div class="bg-white shadow-lg p-6 rounded-lg">
                    <p class="text-lg">"Service exceptionnel ! Ma voiture est comme neuve."</p>
                    <p class="text-sm font-bold mt-2">- Pierre L.</p>
                </div>
                <div class="bg-white shadow-lg p-6 rounded-lg">
                    <p class="text-lg">"Le polissage a redonné une seconde vie à ma carrosserie."</p>
                    <p class="text-sm font-bold mt-2">- Sophie D.</p>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center">FAQ</h2>
            <div class="mt-6">
                <details class="collapse bg-white shadow-md rounded-lg mb-4">
                    <summary class="collapse-title text-lg font-bold">Combien de temps dure un detailing complet ?</summary>
                    <div class="collapse-content">
                        <p>Un detailing complet prend en moyenne entre 6 et 8 heures.</p>
                    </div>
                </details>
                <details class="collapse bg-white shadow-md rounded-lg mb-4">
                    <summary class="collapse-title text-lg font-bold">Utilisez-vous des produits écologiques ?</summary>
                    <div class="collapse-content">
                        <p>Oui, nous utilisons des produits respectueux de l'environnement.</p>
                    </div>
                </details>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="container mx-auto my-16 text-center px-6">
            <h2 class="text-4xl font-bold">Réservez votre detailing dès maintenant !</h2>
            <a href="{{ route('rendezvous.create') }}" class="btn btn-primary mt-6">Prendre Rendez-vous</a>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8 text-center">
            <p>© 2025 BDT - Tous droits réservés</p>
            <div class="flex justify-center space-x-6 mt-4">
                <a href="#" class="text-xl"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-xl"><i class="fab fa-instagram"></i></a>
            </div>
        </footer>
    </div>

    <!-- Effet de brillance automatique -->
    <style>
        .shine-text {
            position: relative;
            display: inline-block;
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.3), rgba(16, 185, 129, 1), rgba(16, 185, 129, 0.3));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 4s infinite linear;
        }

        @keyframes shine {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>


</x-app-layout>
