@if(Auth::user())
<nav class="navbar bg-base-100 border-b border-gray-200">
    <div class="navbar-start">
        <!-- Menu Mobile (DaisyUI Dropdown) -->
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li><a href="{{ route('rendezvous.index') }}">{{ __('Mes rendez-vous') }}</a></li>
                <li><a href="{{ route('planning.index') }}">{{ __('Planning') }}</a></li>
                <li><a href="{{ route('availabilities.index') }}">{{ __('Dispo') }}</a></li>
                availabilities

                @if(Auth::user()->role === 'admin')
                    <li>
                        <details>
                            <summary>{{ __('Gestion') }}</summary>
                            <ul class="p-2">

                                <li><a href="{{ route('prestations.index') }}">{{ __('Services') }}</a></li>
                                <li><a href="{{ route('garages.index') }}">{{ __('Garages') }}</a></li>
                                <li><a href="{{ route('admin.users') }}">{{ __('Gestion des utilisateurs') }}</a></li>
                            </ul>
                        </details>
                    </li>
                @endif

                <li><a href="{{ route('profile.edit') }}">{{ __('Profil') }}</a></li>
            </ul>
        </div>
    </div>

    <div class="navbar-center">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <div class="navbar-end flex items-center space-x-2">

        <!-- Menu Utilisateur -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff" alt="Avatar">
                </div>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                <li>
                    <a href="{{ route('profile.edit') }}">
                        {{ __('Profil') }}
                        @if(Auth::user()->role === 'admin')
                            <span class="badge badge-error ml-2">Admin</span>
                        @endif
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">{{ __('Déconnexion') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endif
