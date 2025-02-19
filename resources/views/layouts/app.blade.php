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

@if (session('toast'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast("{{ session('toast.message') }}", "{{ session('toast.type') }}");
        });
    </script>
@endif


</body>
</html>
