<x-app-layout>

<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Paramètres de l'application</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf
        <div class="overflow-x-auto rounded shadow">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Clé</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $setting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b text-gray-600">{{ $setting->key }}</td>
                            <td class="px-4 py-2 border-b">
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 transition">Enregistrer</button>
        </div>
    </form>
</div>

</x-app-layout>
