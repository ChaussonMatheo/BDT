<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de demande - B-CLEAN Detailing</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header img {
            height: 48px;
        }
        .header h1 {
            font-size: 20px;
            margin-top: 10px;
            color: #111111;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            font-size: 15px;
            color: #111111;
            line-height: 1.6;
        }
        .content h3 {
            margin-top: 20px;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
            color: #111111;
        }
        .highlight {
            font-weight: 500;
        }
        .btn {
            display: inline-block;
            margin: 25px auto;
            padding: 12px 24px;
            background-color: #111111;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            font-size: 14px;
        }
        .footer {
            font-size: 12px;
            color: #666666;
            text-align: center;
            margin-top: 40px;
            border-top: 1px solid #eeeeee;
            padding-top: 15px;
        }
        ul {
            padding-left: 18px;
            margin: 12px 0;
        }
        li {
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <img src="https://b-clean.bzh/images/BAUDET_LOGO.svg" alt="Logo B-CLEAN">
        <h1>Rendez-vous en attente</h1>
    </div>

    <!-- CONTENU -->
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $rendezVous->guest_name ?? $rendezVous->user->name }}</span>,</p>

        <p>Nous avons bien reçu votre demande de rendez-vous chez <strong>B-CLEAN Detailing</strong>.</p>

        <p>Celui-ci est actuellement <strong>en attente de confirmation</strong>.</p>

        <h3>Détails de votre rendez-vous</h3>
        <ul>
            <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y') }}</li>
            <li><strong>Heure :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('H:i') }}</li>
            <li><strong>Prestation :</strong> {{ $rendezVous->prestation->service }}</li>
            <li><strong>Statut :</strong> En attente</li>
        </ul>

        <p>Vous recevrez un email dès que nous aurons confirmé le rendez-vous.</p>

        <p>Pour toute question, n'hésitez pas à nous contacter.</p>

        <p>Merci de votre confiance,<br>L'équipe <strong>B-CLEAN Detailing</strong></p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        &copy; {{ date('Y') }} B-CLEAN Detailing — Tous droits réservés<br>
        <a href="{{ url('/') }}">Site web</a> |
        <a href="mailto:contact@b-clean.bzh">Contact</a>
    </div>
</div>
</body>
</html>
