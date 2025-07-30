<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture - B-CLEAN - {{ $reservation->garage->nom }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <img src="https://b-clean.bzh/images/BAUDET_LOGO.svg" alt="B-CLEAN Logo" style="width: 150px; margin-bottom: 20px;">

        <p>Bonjour,</p>

        <p>Vous trouverez en pièce jointe la facture à destination de <strong>{{ $reservation->garage->nom }}</strong>.</p>

        <div style="background: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Détails de la prestation :</h3>
            <p><strong>Garage :</strong> {{ $reservation->garage->nom }}</p>
            <p><strong>Période :</strong> du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</p>
            <p><strong>Total :</strong> {{ number_format($reservation->prestations->sum('montant'), 2, ',', ' ') }} €</p>
        </div>

        <p>Nous vous remercions de votre confiance.</p>

        <p>Cordialement,<br>
        <strong>B-CLEAN - Mathéo Baudet</strong></p>
        <a href="mailto:contact@b-clean.bzh">contact@b-clean.bzh</a>
    </div>
</body>
</html>

