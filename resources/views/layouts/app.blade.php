<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Alpine.js -->
</head>
<body class="font-sans antialiased">

<!-- Conteneur principal -->
<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')
    <div class="drawer drawer">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-side z-[1000]">
            <label for="admin-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4 flex flex-col justify-between">
                <div>
                    <li class="menu-title"><i class="fas fa-user-shield"></i> Administration</li>
                    <li><a href="{{ route('prestations.index') }}"><i class="fas fa-wrench"></i> Services</a></li>
                    <li><a href="{{ route('garages.index') }}"><i class="fas fa-warehouse"></i> Garages</a></li>
                    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users-cog"></i> Gestion des utilisateurs</a></li>
                    <li><a href="{{ route('availabilities.index') }}"><i class="fas fa-clock"></i> Disponibilités</a></li>
                </div>

                <!-- Affichage de la version -->
                <div class="text-gray-500 text-sm text-center mt-6">
                    Version {{ config('app.version') }}
                </div>
            </ul>
        </div>

    </div>
    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
    @include('partial.footer')

</div>



<!-- Toast Container -->
<div x-data="{ show: false, message: '', type: 'success' }"
     x-show="show"
     x-transition.opacity.out.duration.500ms
     class="fixed bottom-5 right-5 z-50 p-4 rounded-lg shadow-lg text-white"
     :class="{
         'bg-green-500': type === 'success',
         'bg-blue-500': type === 'info',
         'bg-yellow-500': type === 'warning',
         'bg-red-500': type === 'error'
     }">
    <span x-text="message"></span>
</div>

<script>
    function showToast(message, type = 'success') {
        let toast = document.querySelector('[x-data]');
        toast.__x.$data.message = message;
        toast.__x.$data.type = type;
        toast.__x.$data.show = true;

        setTimeout(() => {
            toast.__x.$data.show = false;
        }, 3000);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

@if (session('toast'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast("{{ session('toast.message') }}", "{{ session('toast.type') }}");
        });
    </script>
@endif


</body>
</html>
