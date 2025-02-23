<x-app-layout>
    @if (!Auth::check())
        <div class="navbar bg-white shadow-md">
            <!-- Navbar Start (Logo & Menu Mobile) -->
            <div class="navbar-start">
                <div class="dropdown">
                    <button tabindex="0" class="btn btn-ghost lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                    </button>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-emerald-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">Nos Services</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

            </div>

            <!-- Navbar Center (Menu Desktop) -->
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 text-emerald-700">
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Nos Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- Navbar End (Boutons de Connexion/Inscription) -->
            <div class="navbar-end">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline btn-emerald">Se connecter</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-emerald ml-2">S'inscrire</a>
            </div>
        </div>
    @endif

    <div class="min-h-screen bg-gray-100">

        <section class="hero min-h-screen bg-white text-emerald-600 flex items-center">
            <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6">
                <!-- Image à gauche -->
                <div class="w-full flex justify-center">
                    <img src="{{ asset('images/car.png') }}" alt="Detailing Automobile"
                         class="max-w-sm md:max-w-lg rounded-lg">

                </div>

                <!-- Texte à droite (remplacé par un SVG) -->
                <div class="w-full md:w-1/2 text-right relative">
                    <img src="{{ asset('images/test_logo2.svg') }}" alt="Impeccable"
                         class="w-96 md:w-[500px] text-emerald-500 shine-svg ">

                    <p class="mt-4 text-lg md:text-xl text-blue-950 mr-12">
                        Offrez à votre voiture un soin d’exception.
                    </p>
                    <a href="{{ route('rendezvous.create') }}"
                       class="btn btn-primary mt-6  text-lg md:text-xl flex items-center justify-center">
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
        .shine-svg {
            opacity: 1 !important;
            visibility: visible !important;
            position: relative;
            display: inline-block;
            mask-image: linear-gradient(90deg, rgba(16, 185, 129, 0.3), rgba(16, 185, 129, 1), rgba(16, 185, 129, 0.3));
            -webkit-mask-image: linear-gradient(90deg, rgba(16, 185, 129, 0.3), rgba(16, 185, 129, 1), rgba(16, 185, 129, 0.3));
            mask-size: 200% auto;
            -webkit-mask-size: 200% auto;
            animation: shine 4s infinite linear;
        }

        @keyframes shine {
            0% { mask-position: -200% 0; -webkit-mask-position: -200% 0; }
            100% { mask-position: 200% 0; -webkit-mask-position: 200% 0; }
        }

    </style>


</x-app-layout>
