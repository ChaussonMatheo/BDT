<x-guest-layout>

        <h2 class="text-2xl font-semibold text-center text-gray-700">Créer un compte</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Nom -->
            <div>
                <label for="name" class="label"><span class="label-text">Nom</span></label>
                <input id="name" type="text" name="name" class="input input-bordered w-full" value="{{ old('name') }}" required autofocus>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="label"><span class="label-text">Adresse e-mail</span></label>
                <input id="email" type="email" name="email" class="input input-bordered w-full" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Téléphone -->
            <div>
                <label for="phone" class="label"><span class="label-text">Téléphone</span></label>
                <input id="phone" type="text" name="phone" class="input input-bordered w-full" value="{{ old('phone') }}" required>
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="label"><span class="label-text">Mot de passe</span></label>
                <div class="relative">
                    <input id="password" type="password" name="password" class="input input-bordered w-full pr-10" required>
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-2 flex items-center">
                        <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5c-4.75 0-8.5 3.75-8.5 7.5s3.75 7.5 8.5 7.5 8.5-3.75 8.5-7.5-3.75-7.5-8.5-7.5zm0 3a4.5 4.5 0 110 9 4.5 4.5 0 010-9z"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="password_confirmation" class="label"><span class="label-text">Confirmer le mot de passe</span></label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="input input-bordered w-full pr-10" required>
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-2 flex items-center">
                        <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5c-4.75 0-8.5 3.75-8.5 7.5s3.75 7.5 8.5 7.5 8.5-3.75 8.5-7.5-3.75-7.5-8.5-7.5zm0 3a4.5 4.5 0 110 9 4.5 4.5 0 010-9z"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Boutons -->
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Déjà inscrit ?</a>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
        </form>

    <!-- Script pour afficher/masquer le mot de passe -->
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById('eye-' + id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.add("text-blue-500");
            } else {
                input.type = "password";
                icon.classList.remove("text-blue-500");
            }
        }
    </script>
</x-guest-layout>
