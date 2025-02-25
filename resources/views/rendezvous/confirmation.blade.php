<x-app-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-center">

        <!-- Animation de succès -->
        <div class="success-animation mx-auto">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <!-- Message de confirmation -->
        <h1 class="text-2xl font-bold text-green-600 mt-4">Votre rendez-vous est enregistré !</h1>
        <p class="mt-4 text-gray-600">
            Nous avons bien pris en compte votre demande de rendez-vous.
        </p>
        <p class="text-gray-600">
            Vous recevrez un e-mail de confirmation dès que votre rendez-vous sera <strong>confirmé</strong> ou <strong>annulé</strong>.
        </p>

        <!-- Bouton Retour à l'accueil -->
        <div class="mt-6">
            <a href="{{ url('/') }}" class="btn btn-success text-white">Retour à l'accueil</a>
        </div>
    </div>

    <!-- Styles et animations -->
    <style>
        .success-animation { margin: 0 auto; width: 100px; }

        .checkmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #4bb71b;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #4bb71b;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
            position: relative;
            margin: 0 auto;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #4bb71b;
            fill: #fff;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }

        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }

        @keyframes fill {
            100% { box-shadow: inset 0px 0px 0px 30px #4bb71b; }
        }
    </style>
</x-app-layout>
