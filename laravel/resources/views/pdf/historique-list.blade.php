<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { position: absolute; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #777; }
        .status-validee { color: green; }
        .status-rejetee { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historique des Demandes</h1>
        <p>Généré le: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Demande</th>
                <th>Étudiant</th>
                <th>Type Document</th>
                <th>Créée le</th>
                <th>Traitée le</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demandes as $demande)
            <tr>
                <td>{{ $demande->num_demande }}</td>
                <td>{{ $demande->etudiant->nom }} {{ $demande->etudiant->prenom }} ({{ $demande->etudiant->apogee }})</td>
                <td>{{ $demande->getTypeDocumentLabel() }}</td>
                <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                <td>{{ $demande->updated_at->format('d/m/Y') }}</td>
                <td class="status-{{ $demande->status }}">{{ ucfirst($demande->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        UniServices - Portail Administratif
    </div>
</body>
</html>
