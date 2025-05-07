<!-- resources/views/emails/rendezvous-admin.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau rendez-vous</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-white text-black font-sans text-sm">
<div class="max-w-lg mx-auto border border-gray-300 rounded p-6">
    <h1 class="text-xl font-bold mb-4">🔔 Nouveau rendez-vous</h1>

    <p class="mb-2"><strong>Client :</strong> {{ $client }}</p>
    <p class="mb-2"><strong>Prestation :</strong> {{ $prestation }}</p>
    <p class="mb-6"><strong>Date :</strong> {{ $date }}</p>

    <a href="{{ url('/dashboard') }}" class="inline-block bg-black text-white text-center px-5 py-2 rounded font-semibold text-sm hover:opacity-90 transition">
        Accéder au tableau de bord
    </a>

    <p class="mt-8 text-xs text-gray-600">Ce message vous est envoyé automatiquement par la plateforme B-CLEAN.</p>
</div>
</body>
</html>
