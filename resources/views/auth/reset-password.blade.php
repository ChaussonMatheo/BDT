<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Jeton de réinitialisation du mot de passe -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Adresse e-mail -->
        <div>
            <x-input-label for="email" :value="'Adresse e-mail'" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Nouveau mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Nouveau mot de passe'" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Confirmer le mot de passe'" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ 'Réinitialiser le mot de passe' }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
