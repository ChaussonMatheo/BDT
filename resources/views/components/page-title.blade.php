<div class="flex items-center justify-between w-full mb-4">
    <!-- Titre à gauche -->
    <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>

    <!-- Fil d'Ariane (Breadcrumb) à droite -->
    <div class="text-sm breadcrumbs">
        <ul class="flex items-center text-gray-500">
            <li><a href="{{ route('dashboard') }}" class="hover:text-primary">BDT</a></li>
            @if(isset($breadcrumb))
                <li>{{ $breadcrumb }}</li>
            @endif
        </ul>
    </div>
</div>
