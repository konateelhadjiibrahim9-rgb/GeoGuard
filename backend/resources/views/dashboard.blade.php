<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoGuard - Tableau de Bord</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        #map {
            height: 600px;
            width: 100%;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #667eea;
        }
        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
        }
        .locations-table {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🗺️ GeoGuard - Tableau de Bord</h1>
        <p>Surveillance en temps réel des localisations</p>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3>Total des localisations</h3>
                <div class="number">{{ $locations->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Appareils actifs</h3>
                <div class="number">{{ $locations->pluck('device_id')->unique()->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Utilisateurs</h3>
                <div class="number">{{ $locations->pluck('user_id')->unique()->count() }}</div>
            </div>
        </div>

        <div class="stat-card" style="margin-bottom: 20px;">
            <h2>📍 Carte interactive</h2>
            <div id="map"></div>
        </div>

        <div class="locations-table">
            <h2>📋 Dernières localisations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Appareil</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                    <tr>
                        <td>{{ $location->id }}</td>
                        <td>{{ $location->user->name ?? 'Inconnu' }}</td>
                        <td>{{ $location->device_id }}</td>
                        <td>{{ $location->latitude }}</td>
                        <td>{{ $location->longitude }}</td>
                        <td>{{ $location->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialiser la carte
        var map = L.map('map').setView([48.8566, 2.3522], 5);

        // Ajouter les tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Charger les données de localisation
        fetch('/api/map-data')
            .then(response => response.json())
            .then(data => {
                data.forEach(location => {
                    var marker = L.marker([location.latitude, location.longitude])
                        .addTo(map)
                        .bindPopup(`
                            <strong>Utilisateur:</strong> ${location.user_name}<br>
                            <strong>Appareil:</strong> ${location.device_id}<br>
                            <strong>Date:</strong> ${location.created_at}<br>
                            <strong>Position:</strong> ${location.latitude}, ${location.longitude}
                        `);
                });

                // Ajuster la vue pour inclure tous les marqueurs
                if (data.length > 0) {
                    var group = new L.featureGroup(data.map(loc => 
                        L.marker([loc.latitude, loc.longitude])
                    ));
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            })
            .catch(error => console.error('Erreur lors du chargement des données:', error));
    </script>
</body>
</html>
