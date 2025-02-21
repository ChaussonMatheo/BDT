<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md text-center">
        <h1 class="text-2xl font-bold text-green-600">Votre rendez-vous est enregistré !</h1>
        <p class="mt-4 text-gray-600">Nous avons bien pris en compte votre demande de rendez-vous.</p>
        <p class="text-gray-600">Vous recevrez un e-mail de confirmation dès que votre rendez-vous sera <strong>confirmé</strong> ou <strong>annulé</strong>.</p>

        <div class="mt-6">
            <a href="{{ url('/') }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700">
                Retour à l'accueil
            </a>
        </div>
    </div>
</x-app-layout>
