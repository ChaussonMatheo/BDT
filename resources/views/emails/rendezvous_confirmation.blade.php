<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de rendez-vous</title>
</head>
<body>
<h2>Bonjour,</h2>
<p>Votre rendez-vous a bien été enregistré.</p>
<ul>
    <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y H:i') }}</li>
    <li><strong>Service :</strong> {{ $service }}</li>
</ul>
<p>Merci de votre confiance.</p>
</body>
</html>
