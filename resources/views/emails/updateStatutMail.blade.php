<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if($rendezVous->statut == "confirmé") Confirmation @else Annulation @endif de votre rendez-vous</title>
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
            border: 2px solid @if($rendezVous->statut == "confirmé") #10B981 @else #EF4444 @endif; /* Vert ou Rouge */
        }
        .header {
            background-color: @if($rendezVous->statut == "confirmé") #047857 @else #B91C1C @endif; /* Vert foncé ou Rouge */
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
            background-color: @if($rendezVous->statut == "confirmé") #10B981 @else #F87171 @endif;
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }
        .btn:hover {
            background-color: @if($rendezVous->statut == "confirmé") #059669 @else #DC2626 @endif;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            padding: 10px;
            border-top: 1px solid #eeeeee;
        }
        .highlight {
            color: @if($rendezVous->statut == "confirmé") #047857 @else #B91C1C @endif;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="header">
        @if($rendezVous->statut == "confirmé")
            ✅ Confirmation de votre rendez-vous
        @else
            ❌ Annulation de votre rendez-vous
        @endif
    </div>

    <!-- CONTENU -->
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $rendezVous->guest_name ?? $rendezVous->user->name }}</span>,</p>

        @if($rendezVous->statut == "confirmé")
            <p>Votre rendez-vous a bien été <strong>confirmé</strong> chez <strong>BDT</strong>.</p>
        @else
            <p>Nous sommes désolés, votre rendez-vous a été <strong>annulé</strong>.</p>
            <p>Vous pouvez reprendre rendez-vous à tout moment en cliquant sur le bouton ci-dessous.</p>
        @endif

        <h3 class="highlight">Détails du rendez-vous :</h3>
        <ul>
            <li><strong>Date : </strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->translatedFormat('l d F Y') }}</li>
            <li><strong>Heure : </strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('H:i') }}</li>
            <li><strong>Prestation : </strong> {{ $rendezVous->prestation->service }}</li>
            <li><strong>Statut : </strong> <span class="highlight">{{ ucfirst($rendezVous->statut) }}</span></li>
        </ul>

        @if($rendezVous->statut == "confirmé")
            <p>Vous pouvez gérer votre rendez-vous en cliquant sur le bouton ci-dessous :</p>
            <a href="{{ $manageUrl }}" class="btn">Gérer mon rendez-vous</a>
            <a href="{{ $icsLink }}" class="btn">📅 Ajouter à mon calendrier</a>
        @else
            <a href="{{ url('/rendezVous/create') }}" class="btn">📆 Prendre un nouveau rendez-vous</a>
        @endif

        <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

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
