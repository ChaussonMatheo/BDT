<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 13px; color: #111; background: #fff; margin: 0; padding: 2rem; }
        .header, .footer { text-align: center; margin-bottom: 1.5rem; }
        .logo { height: 60px; margin-bottom: 0.5rem; }
        .title { font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 2px solid #111; padding-bottom: 0.5rem; }

        .info-table { width: 100%; margin-top: 1rem; margin-bottom: 2rem; }
        .info-table td { padding: 4px 0; }

        .table {
            width: 100%; border-collapse: collapse; margin-top: 1rem;
            border: 1px solid #ccc;
        }
        .table th {
            background: #111; color: #fff;
            padding: 8px; font-size: 0.85rem;
        }
        .table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .total-row td {
            font-weight: bold;
            background: #f3f3f3;
        }

        .footer p {
            font-size: 0.8rem;
            color: #444;
            margin-top: 2rem;
            border-top: 1px dashed #ccc;
            padding-top: 1rem;
        }

        .legal {
            margin-top: 2rem;
            font-size: 0.9rem;
            text-align: left;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <img src="{{ public_path('images/BAUDET_LOGO.svg') }}" class="logo" alt="Logo">
    <div class="title">Facture - Prestations de service</div>
</div>

<!-- Informations générales -->
<table class="info-table">
    <tr>
        <td><strong>Client :</strong></td>
        <td>{{ $rendezVous->guest_name ?? 'Client non renseigné' }}</td>
    </tr>
    @if($rendezVous->guest_email)
    <tr>
        <td><strong>Email :</strong></td>
        <td>{{ $rendezVous->guest_email }}</td>
    </tr>
    @endif
    @if($rendezVous->guest_phone)
    <tr>
        <td><strong>Téléphone :</strong></td>
        <td>{{ $rendezVous->guest_phone }}</td>
    </tr>
    @endif
    <tr>
        <td><strong>Date de service :</strong></td>
        <td>{{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('d/m/Y à H:i') }}</td>
    </tr>
    @if($rendezVous->type_de_voiture)
    <tr>
        <td><strong>Type de véhicule :</strong></td>
        <td>
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
        </td>
    </tr>
    @endif
</table>

<!-- Table des prestations -->
<table class="table">
    <thead>
    <tr>
        <th>Prestation</th>
        <th style="width: 120px;">Montant (€)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($prestations as $prestation)
        <tr>
            <td>{{ $prestation }}</td>
            <td>-</td>
        </tr>
    @endforeach
    <tr class="total-row">
        <td>Total TTC</td>
        <td>{{ number_format($rendezVous->tarif, 2, ',', ' ') }} €</td>
    </tr>
    </tbody>
</table>

@if($rendezVous->notes)
<!-- Notes -->
<div style="margin-top: 1.5rem;">
    <strong>Notes :</strong><br>
    {{ $rendezVous->notes }}
</div>
@endif

<!-- Informations légales -->
<div class="legal">
    <p><strong>Émetteur :</strong> {{ $legal_emetteur }}</p>
    <p><strong>SIRET :</strong> {{ $legal_siret }}</p>
    <p><strong>IBAN :</strong> {{ $legal_iban }}</p>
</div>

<!-- Footer avec clause -->
<div class="footer">
    <p>
        <strong>Clause :</strong> Toute facture est payable à réception, délai maximum 30 jours.
        Des pénalités de retard peuvent être appliquées selon l'article L.441-6 du Code de commerce.
    </p>
    <p>Merci de votre confiance.<br>— L'équipe B-CLEAN —</p>
</div>

</body>
</html>
