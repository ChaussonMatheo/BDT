<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre rendez-vous</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #ECFDF5; /* Vert clair DaisyUI Emerald */
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
            border: 2px solid #10B981; /* Vert DaisyUI Emerald */
        }
        .header {
            background-color: #047857; /* Vert foncé Emerald */
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            font-size: 20px;
            font-weight: bold;
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
            background-color: #10B981; /* Vert Emerald */
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }
        .btn:hover {
            background-color: #059669; /* Vert plus foncé pour hover */
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            padding: 10px;
            border-top: 1px solid #eeeeee;
        }
        .highlight {
            color: #047857; /* Vert foncé DaisyUI */
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="header">
        Confirmation de votre rendez-vous - BDT Detailing
    </div>

    <!-- CONTENU -->
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $rendezVous->guest_name ?? $rendezVous->user->name }}</span>,</p>

        <p>Votre rendez-vous a bien été enregistré dans notre centre de <strong>detailing automobile</strong> **BDT**.</p>

        <h3 class="highlight">Détails du rendez-vous :</h3>
        <ul>
            <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y') }}</li>
            <li><strong>Heure :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('H:i') }}</li>
            <li><strong>Prestation :</strong> {{ $rendezVous->prestation->nom }}</li>
            <li><strong>Statut :</strong> {{ ucfirst($rendezVous->statut) }}</li>
        </ul>

        <p>Vous pouvez gérer votre rendez-vous en cliquant sur le bouton ci-dessous :</p>

        <!-- BOUTON GÉRER LE RENDEZ-VOUS -->
        <a href="{{ $manageUrl }}" class="btn">Gérer mon rendez-vous</a>

        <p>Si vous souhaitez modifier ou annuler votre rendez-vous, veuillez nous contacter.</p>

        <p>À très bientôt chez <strong>BDT Detailing</strong> !</p>
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
