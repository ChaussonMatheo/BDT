<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst($rendezVous->statut) }} de votre rendez-vous - B-CLEAN</title>
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
        }
        .highlight {
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            margin: 20px 0;
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
        <h1>
            @if($rendezVous->statut === 'confirmé')
                ✅ Rendez-vous confirmé
            @elseif($rendezVous->statut === 'annulé')
                ❌ Rendez-vous annulé
            @else
                ⚠️ Statut : {{ ucfirst($rendezVous->statut) }}
            @endif
        </h1>
    </div>

    <!-- CONTENU -->
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $rendezVous->guest_name ?? $rendezVous->user->name }}</span>,</p>

        @if($rendezVous->statut === 'confirmé')
            <p>Votre rendez-vous chez <strong>B-CLEAN Detailing</strong> a bien été <strong>confirmé</strong>.</p>
        @elseif($rendezVous->statut === 'annulé')
            <p>Nous sommes désolés, votre rendez-vous a été <strong>annulé</strong>.</p>
            <p>Vous pouvez reprendre rendez-vous à tout moment via le lien ci-dessous.</p>
        @else
            <p>Le statut de votre rendez-vous est actuellement : <strong>{{ ucfirst($rendezVous->statut) }}</strong>.</p>
        @endif

        <h3>Détails du rendez-vous</h3>
        <ul>
            <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y') }}</li>
            <li><strong>Heure :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('H:i') }}</li>
            <li><strong>Prestation :</strong> {{ $rendezVous->prestation->service }}</li>
            <li><strong>Statut :</strong> {{ ucfirst($rendezVous->statut) }}</li>
        </ul>

        @if($rendezVous->statut === 'confirmé')
            <p>Vous pouvez gérer votre rendez-vous à tout moment :</p>
            <a href="{{ $manageUrl }}" class="btn">Gérer mon rendez-vous</a>
            <a href="{{ $icsLink }}" class="btn">📅 Ajouter à mon calendrier</a>
        @elseif($rendezVous->statut === 'annulé')
            <a href="{{ url('/rendezVous/create') }}" class="btn">📆 Prendre un nouveau rendez-vous</a>
        @endif

        <p>Pour toute question, vous pouvez nous contacter via notre site.</p>

        <p>À bientôt,<br>L'équipe <strong>B-CLEAN Detailing</strong></p>
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
