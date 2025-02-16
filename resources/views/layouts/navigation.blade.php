<nav class="navbar bg-base-100 border-b border-gray-200">
    <div class="max-w-7xl mx-auto flex-1 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center w-full">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-semibold">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden lg:flex space-x-6">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>

                @if(Auth::user()->role === 'admin')

                    <x-nav-link :href="route('planning.index')" :active="request()->routeIs('planning.index')">
                        {{ __('Planning') }}
                    </x-nav-link>
                    <x-nav-link :href="route('prestations.index')" :active="request()->routeIs('prestations.index')">
                        {{ __('Services') }}
                    </x-nav-link>
                    <x-nav-link :href="route('garages.index')" :active="request()->routeIs('garages.index')">
                        {{ __('Garages') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        {{ __('Gestion des utilisateurs') }}
                    </x-nav-link>
                @endif
            </div>


            <!-- Menu Utilisateur -->
            <div class="hidden lg:flex items-center space-x-4">
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

            <!-- Menu Mobile -->
            <div class="lg:hidden dropdown dropdown-end">
                <button tabindex="0" class="btn btn-ghost btn-circle">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    @if(Auth::user()->role === 'admin')
                        <li><a href="{{ route('admin.users') }}">{{ __('Gestion des utilisateurs') }}</a></li>
                        <li><a href="{{ route('admin.users') }}">{{ __('Paramètres') }}</a></li>
                    @endif
                    <li><a href="{{ route('profile.edit') }}">{{ __('Profil') }}</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">{{ __('Déconnexion') }}</button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>
