<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mise à jour des informations -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-semibold">{{ __('Mise à jour des informations') }}</h2>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Changement de mot de passe -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-semibold">{{ __('Modifier le mot de passe') }}</h2>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Suppression de compte -->
            <div class="card bg-red-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-semibold text-red-600">{{ __('Supprimer mon compte') }}</h2>
                    <p class="text-sm text-gray-600">Cette action est irréversible. Toutes vos données seront supprimées.</p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
