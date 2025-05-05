<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relance Client - {{ $client->raison_sociale }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $sousModele->titre }}</h2>
        <p><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <p><strong>Client :</strong> {{ $client->raison_sociale }} ({{ $client->code_client }})</p>
        <p><strong>Adresse :</strong> {{ $client->adresse ?? 'N/A' }}</p>
    </div>

    <div class="section">
        <p class="bold">Texte du Sous-Modèle :</p>
        <p>{!! nl2br(e($sousModele->texte['contenu'] ?? '')) !!}</p>
    </div>

    <div class="section">
        <p class="bold">Détails du Relevé :</p>
        <p><strong>Code relevé :</strong> {{ $releve->code_releve }}</p>
        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($releve->date_releve)->format('d/m/Y') }}</p>
    </div>

    <div class="section">
    <p class="bold">Lignes du relevé :</p>

    @if(!empty($releve->lignes) && count($releve->lignes) > 0)
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
            @foreach($releve->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->description }}</td>
                    <td>{{ number_format($ligne->montant, 2, ',', ' ') }} MAD</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune ligne disponible pour ce relevé.</p>
    @endif
</div>


</body>
</html>
