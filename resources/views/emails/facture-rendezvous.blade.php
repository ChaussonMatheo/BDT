<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture - Prestations de service</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">Facture - Prestations de service</h2>

        <p>Bonjour {{ $rendezVous->guest_name ?? 'Cher client' }},</p>

        <p>Vous trouverez en pièce jointe la facture pour les prestations de service réalisées.</p>

        <div style="background: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Détails de la prestation :</h3>
            <p><strong>Date de service :</strong> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('d/m/Y à H:i') }}</p>
            <p><strong>Services :</strong> {{ $rendezVous->prestation_libre }}</p>
            @if($rendezVous->type_de_voiture)
                <p><strong>Véhicule :</strong>
                    @switch($rendezVous->type_de_voiture)
                        @case('petite_voiture')
                            Petite voiture
                            @break
                        @case('berline')
                            Berline
                            @break
                        @case('suv_4x4')
                            SUV / 4x4
                            @break
                        @default
                            {{ $rendezVous->type_de_voiture }}
                    @endswitch
                </p>
            @endif
            <p><strong>Montant total :</strong> {{ number_format($rendezVous->tarif, 2, ',', ' ') }} €</p>
        </div>

        @if($rendezVous->notes)
        <div style="margin: 20px 0;">
            <p><strong>Notes :</strong><br>{{ $rendezVous->notes }}</p>
        </div>
        @endif

        <p>Nous vous remercions de votre confiance et espérons vous revoir bientôt.</p>

        <p>Cordialement,<br>
        <strong>B-CLEAN - Mathéo Baudet</strong></p>
    </div>
</body>
</html>

