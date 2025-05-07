@if(Auth::user())
    <nav class="navbar bg-base-100 border-b border-gray-200">
        <div class="navbar-start">
            <!-- Menu Mobile (DaisyUI Dropdown) -->
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <i class="fas fa-bars text-xl"></i>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[10] mt-3 w-52 p-2 shadow">
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ route('rendezvous.index') }}"><i class="fas fa-calendar-alt"></i> Mes rendez-vous</a></li>
                    <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> Profil</a></li>
                </ul>
            </div>
        </div>

        <div class="navbar-center">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>

        <div class="navbar-end flex items-center space-x-4">
            <!-- Icône Paramètres pour ouvrir le Drawer Admin -->
            @if(Auth::user()->role === 'admin')
                <label for="admin-drawer" class="btn btn-ghost btn-circle">
                    <i class="fas fa-cog text-xl"></i>
                </label>
            @endif

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
                            <i class="fas fa-user"></i> Profil
                            @if(Auth::user()->role === 'admin')
                                <span class="badge badge-error ml-2">Admin</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endif
