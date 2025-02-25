<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre rendez-vous est en attente de confirmation</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #FACC15; /* Jaune - Attente */
        }
        .header {
            background-color: #FBBF24; /* Orange foncé - Attente */
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            color: #333333;
        }
        .btn {
            display: block;
            width: 80%;
            margin: 20px auto;
            text-align: center;
            background-color: #FACC15; /* Jaune */
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }
        .btn:hover {
            background-color: #D97706; /* Orange foncé */
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            padding: 10px;
            border-top: 1px solid #eeeeee;
        }
        .highlight {
            color: #D97706; /* Orange foncé */
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="header">
        ⏳ Votre rendez-vous est en attente de confirmation
    </div>

    <!-- CONTENU -->
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $rendezVous->guest_name ?? $rendezVous->user->name }}</span>,</p>

        <p>Nous avons bien enregistré votre demande de rendez-vous chez <strong>BDT Detailing</strong>. Votre rendez-vous est actuellement <span class="highlight">en attente de confirmation</span>.</p>

        <h3 class="highlight">Détails du rendez-vous :</h3>
        <ul>
            <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y') }}</li>
            <li><strong>Heure :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('H:i') }}</li>
            <li><strong>Prestation :</strong> {{ $rendezVous->prestation->service }}</li>
            <li><strong>Statut :</strong> <span class="highlight">En attente de confirmation</span></li>
        </ul>

        <p>Nous vous informerons dès que votre rendez-vous sera confirmé. En attendant, vous pouvez consulter ou modifier votre rendez-vous via votre espace personnel :</p>

        <a href="{{ url('/dashboard') }}" class="btn">Accéder à mon espace</a>

        <p>Si vous avez des questions, n’hésitez pas à nous contacter.</p>

        <p>Merci de votre confiance et à bientôt chez <strong>BDT Detailing</strong> !</p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        &copy; {{ date('Y') }} BDT Detailing - Tous droits réservés. <br>
        <a href="{{ url('/') }}">Visitez notre site</a> |
        <a href="{{ url('/contact') }}">Nous contacter</a>
    </div>
</div>

</body>
</html>
