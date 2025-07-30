<x-app-layout>

    <!-- Swiper.js -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
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
                        <li><a href="#tarif">Nos Services</a></li>
                        <li><a href="#">Réalisations</a></li>
                    </ul>
                </div>

            </div>

            <!-- Navbar Center (Menu Desktop) -->
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 text-black-700">
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#tarif">Nos Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- Navbar End (Boutons de Connexion/Inscription) -->
            <div class="navbar-end">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline btn-emerald">Se connecter</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm btn-emerald ml-2">S'inscrire</a>
            </div>
        </div>
    @endif

    <div class="min-h-screen bg-gray-100">

        <section class="hero min-h-screen bg-white text-emerald-600 flex items-center">
            <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6">
                <!-- Image à gauche -->
                <div class="w-full flex justify-center">
                    <img src="{{ asset('images/car3.png') }}" alt="Detailing Automobile"
                         class="max-w-sm md:max-w-lg rounded-lg">

                </div>

                <!-- Texte à droite (remplacé par un SVG) -->
                <div class="w-full md:w-1/2 text-right relative">
                    <img src="{{ asset('images/BAUDET_LOGO.svg') }}" alt="Impeccable"
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
            <p class="mt-4 text-lg">B-CLEAN est spécialisé dans le detailing automobile, proposant des services de nettoyage et protection haut de gamme.</p>
        </section>

        <!-- Tableau des Prix -->
        <section id="tarif" class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center text-black-600 mb-8">Nos Tarifs</h2>

            <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
                <table class="table table-zebra w-full">
                    <thead class="bg-black text-white">
                    <tr>
                        <th><i class="fa-solid fa-car mr-2"></i> Service</th>
                        <th><i class="fa-solid fa-car-side mr-2"></i> Petite voiture</th>
                        <th><i class="fa-solid fa-car-rear mr-2"></i> Berline</th>
                        <th><i class="fa-solid fa-truck-monster mr-2"></i> SUV / 4x4</th>
                        <th><i class="fa-regular fa-clock mr-2"></i> Durée estimée</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td class="font-semibold text-black-700">
                                {{ $service->service }}
                                <div class="text-sm text-gray-500">{{ $service->description }}</div>
                            </td>
                            <td>{{ number_format($service->tarif_petite_voiture, 2, ',', ' ') }} €</td>
                            <td>{{ number_format($service->tarif_berline, 2, ',', ' ') }} €</td>
                            <td>{{ number_format($service->tarif_suv_4x4, 2, ',', ' ') }} €</td>
                            <td><span class="badge badge-ghost badge-sm">{{ $service->duree_estimee }} min</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Galerie Avant/Après avec BeerSlider -->
        <section class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center text-black-600">Nos Réalisations</h2>

            @php
                function homeImage($images, $position) {
                    return $images->where('home_position', $position)->first();
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">

                @if (($img1 = homeImage($homeImages, 1)) && ($img2 = homeImage($homeImages, 2)))
                    <div class="relative w-full aspect-[16/9]">
                        <div class="beer-slider" data-beer-label="Avant">
                            <img src="{{ asset('storage/' . $img1->path) }}" alt="Avant {{ $img1->name }}" class="object-cover w-full h-full" />
                            <div class="beer-reveal" data-beer-label="Après">
                                <img src="{{ asset('storage/' . $img2->path) }}" alt="Après {{ $img2->name }}" class="object-cover w-full h-full" />
                            </div>
                        </div>
                    </div>
                @endif

                @if (($img3 = homeImage($homeImages, 3)) && ($img4 = homeImage($homeImages, 4)))
                    <div class="relative w-full aspect-[16/9]">
                        <div class="beer-slider" data-beer-label="Avant">
                            <img src="{{ asset('storage/' . $img3->path) }}" alt="Avant {{ $img3->name }}" class="object-cover w-full h-full" />
                            <div class="beer-reveal" data-beer-label="Après">
                                <img src="{{ asset('storage/' . $img4->path) }}" alt="Après {{ $img4->name }}" class="object-cover w-full h-full" />
                            </div>
                        </div>
                    </div>
                @endif

                @if (($img5 = homeImage($homeImages, 5)) && ($img6 = homeImage($homeImages, 6)))
                    <div class="relative w-full aspect-[16/9]">
                        <div class="beer-slider" data-beer-label="Après">
                            <img src="{{ asset('storage/' . $img5->path) }}" alt="Avant {{ $img5->name }}" class="object-cover w-full h-full" />
                            <div class="beer-reveal" data-beer-label="Avant">
                                <img src="{{ asset('storage/' . $img6->path) }}" alt="Après {{ $img6->name }}" class="object-cover w-full h-full" />
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            @if ($homeImages->isEmpty())
                <p class="text-center text-gray-400 mt-8">Aucune image n’a encore été sélectionnée pour la page d’accueil.</p>
            @endif
        </section>




        <!-- Témoignages Clients -->
        <section id="testimonials" class="relative container mx-auto my-16 px-6 opacity-0">
            <h2 class="text-4xl font-bold text-center">Avis Clients</h2>

            <!-- Indication Swipe -->
            <div id="swipe-hint" class="absolute left-1/2 transform -translate-x-1/2 -bottom-10 flex items-center opacity-70 transition-opacity duration-500">
                <i class="fas fa-hand-pointer text-xl text-gray-500 animate-swipe"></i>
                <span class="ml-2 text-sm text-gray-500">Glissez pour voir plus</span>
            </div>

            <!-- Swiper Container -->
            <div class="mt-6 swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide bg-white shadow-lg p-6 rounded-lg text-center">
                        <p class="text-lg">"Service exceptionnel ! Ma voiture est comme neuve."</p>
                        <div class="flex justify-center mt-2 text-yellow-500">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-sm font-bold mt-2">- Pierre L.</p>
                    </div>

                    <div class="swiper-slide bg-white shadow-lg p-6 rounded-lg text-center">
                        <p class="text-lg">"Le polissage a redonné une seconde vie à ma carrosserie."</p>
                        <div class="flex justify-center mt-2 text-yellow-500">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="text-sm font-bold mt-2">- Sophie D.</p>
                    </div>

                    <div class="swiper-slide bg-white shadow-lg p-6 rounded-lg text-center">
                        <p class="text-lg">"Un travail exceptionnel, voiture comme neuve !"</p>
                        <div class="flex justify-center mt-2 text-yellow-500">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <p class="text-sm font-bold mt-2">- Marc T.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </section>





        <!-- FAQ -->
        <section class="container mx-auto my-16 px-6">
            <h2 class="text-4xl font-bold text-center">FAQ</h2>

            <div class="mt-6 space-y-4">
                <!-- Question 1 -->
                <div tabindex="0" class="collapse collapse-arrow bg-base-200 shadow-md rounded-lg">
                    <div class="collapse-title text-lg font-bold">Combien de temps dure un detailing complet ?</div>
                    <div class="collapse-content">
                        <p>Un detailing complet prend en moyenne entre 6 et 8 heures.</p>
                    </div>
                </div>

                <!-- Question 2 -->
                <div tabindex="0" class="collapse collapse-arrow bg-base-200 shadow-md rounded-lg">
                    <div class="collapse-title text-lg font-bold">Utilisez-vous des produits écologiques ?</div>
                    <div class="collapse-content">
                        <p>Oui, nous utilisons des produits respectueux de l'environnement.</p>
                    </div>
                </div>

                <!-- Question 3 -->
                <div tabindex="0" class="collapse collapse-arrow bg-base-200 shadow-md rounded-lg">
                    <div class="collapse-title text-lg font-bold">Proposez-vous des abonnements pour l'entretien régulier ?</div>
                    <div class="collapse-content">
                        <p>Oui, nous avons plusieurs formules d'abonnement pour un entretien régulier de votre véhicule.</p>
                    </div>
                </div>
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


        @keyframes swipeHint {
            0% { transform: translateX(0); opacity: 1; }
            50% { transform: translateX(-10px); opacity: 0.6; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .animate-swipe {
            animation: swipeHint 1.5s infinite ease-in-out;
        }

        /* Masquer l'indication après la première interaction */
        .hidden-hint {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }




    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".beer-slider").forEach(function (el) {
                new BeerSlider(el);
            });
        });
    </script>

</x-app-layout>
