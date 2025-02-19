<section class="bg-white p-6 rounded-lg ">
    <header class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-user-edit text-blue-500"></i>
            {{ __('Informations du profil') }}
        </h2>
        <p class="text-sm text-gray-600">
            {{ __('Mettez à jour les informations de votre compte et votre adresse e-mail.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <!-- Nom -->
        <div>
            <label for="name" class="label"><span class="label-text"><i class="fas fa-user text-gray-500"></i> Nom</span></label>
            <input id="name" name="name" type="text" class="input input-bordered w-full" value="{{ old('name', $user->name) }}" required>
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="label"><span class="label-text"><i class="fas fa-envelope text-gray-500"></i> Adresse e-mail</span></label>
            <input id="email" name="email" type="email" class="input input-bordered w-full" value="{{ old('email', $user->email) }}" required>
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-gray-700 text-sm">
                    <p>{{ __('Votre adresse e-mail n\'a pas été vérifiée.') }}</p>
                    <button form="send-verification" class="text-blue-600 hover:underline">
                        {{ __('Cliquez ici pour renvoyer l\'e-mail de vérification.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-green-600 text-sm">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Téléphone -->
        <div>
            <label for="phone" class="label"><span class="label-text"><i class="fas fa-phone text-gray-500"></i> Téléphone</span></label>
            <input id="phone" name="phone" type="text" class="input input-bordered w-full" value="{{ old('phone', $user->phone) }}" required>
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600">
                    {{ __('Enregistré.') }}
                </p>
            @endif
        </div>
    </form>
</section>

