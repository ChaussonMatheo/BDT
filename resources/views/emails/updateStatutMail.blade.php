<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de statut de votre rendez-vous</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { background-color: #374165; color: #ffffff; text-align: center; padding: 15px; border-radius: 8px 8px 0 0; font-size: 20px; font-weight: bold; }
        .content { padding: 20px; font-size: 16px; color: #333333; }
        .btn { display: block; width: 80%; margin: 20px auto; text-align: center; background-color: #55a5da; color: white; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn:hover { background-color: #55a5da; }
        .footer { text-align: center; font-size: 14px; color: #777777; padding: 10px; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="header">
        Mise à jour de votre rendez-vous - BDT
    </div>

    <!-- CONTENU -->
    <div class="content">
        <p>Bonjour {{ $rendezVous->guest_name ?? $rendezVous->user->name }},</p>

        <p>Le statut de votre rendez-vous a été mis à jour :</p>

        <h3>Détails du rendez-vous :</h3>
        <ul>
            <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y') }}</li>
            <li><strong>Heure :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('H:i') }}</li>
            <li><strong>Prestation :</strong> {{ $rendezVous->prestation->nom }}</li>
            <li><strong>Ancien statut :</strong> {{ ucfirst($oldStatus) }}</li>
            <li><strong>Nouveau statut :</strong> {{ ucfirst($rendezVous->statut) }}</li>
        </ul>

        <p>Vous pouvez consulter votre rendez-vous via le lien ci-dessous :</p>

        <!-- BOUTON ACCÈS À L'APP -->
        <a href="{{ url('/dashboard') }}" class="btn">Voir mon rendez-vous</a>

        <p>Si vous avez des questions, n’hésitez pas à nous contacter.</p>

        <p>À très bientôt !</p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        &copy; {{ date('Y') }} BDT - Tous droits réservés. <br>
        <a href="{{ url('/') }}">Visitez notre site</a> |
        <a href="{{ url('/contact') }}">Nous contacter</a>
    </div>
</div>

</body>
</html>
