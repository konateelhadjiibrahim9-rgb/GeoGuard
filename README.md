# GeoGuard - Projet de Localisation en Temps Réel

## 📋 Description

GeoGuard est une solution multiplateforme de localisation en temps réel basée sur le consentement. Le projet comprend :

- **Backend API** (Laravel) : Gestion des utilisateurs et stockage des localisations
- **Application Mobile** (Flutter) : Collecte des positions GPS avec authentification
- **Tableau de Bord Web** (Laravel + Leaflet) : Visualisation interactive des localisations

## 🏗️ Architecture du Projet

```
GeoGuard/
├── backend/          # API Laravel
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── Api/LocationController.php
│   │   │   └── DashboardController.php
│   │   └── Models/Location.php
│   ├── database/
│   │   └── migrations/
│   ├── routes/
│   │   ├── api.php
│   │   └── web.php
│   └── resources/views/dashboard.blade.php
└── mobile/           # Application Flutter
    ├── lib/
    │   ├── screens/
    │   │   ├── login_screen.dart
    │   │   ├── register_screen.dart
    │   │   └── home_screen.dart
    │   └── services/
    │       ├── api_service.dart
    │       └── location_service.dart
    └── android/app/src/main/AndroidManifest.xml
```

## 🚀 Installation et Configuration

### Backend (Laravel)

```bash
cd backend
composer install
php artisan migrate
php artisan serve
```

Le serveur sera accessible sur `http://127.0.0.1:8000`

### Application Mobile (Flutter)

```bash
cd mobile
flutter pub get
flutter run
```

## 📡 API Endpoints

### Authentification
- `POST /api/v1/login` - Connexion utilisateur
- `POST /api/v1/register` - Inscription utilisateur

### Localisation
- `POST /api/v1/location` - Envoi des coordonnées GPS
  - Corps de la requête :
    ```json
    {
      "device_id": "string",
      "latitude": number,
      "longitude": number
    }
    ```
- `GET /api/v1/locations` - Récupération de l'historique des localisations

### Web
- `GET /dashboard` - Tableau de bord avec carte interactive
- `GET /api/map-data` - Données JSON pour la carte

## 🗺️ Fonctionnalités

### Phase 1 : Backend ✅
- Configuration Laravel avec SQLite
- Tables utilisateurs et locations
- API REST sécurisée pour la localisation

### Phase 2 : Application Mobile ✅
- Authentification (connexion/inscription)
- Service de géolocalisation en arrière-plan
- Envoi automatique des positions toutes les 5 minutes
- Contrôle utilisateur du partage de localisation

### Phase 3 : Tableau de Bord Web ✅
- Carte interactive avec Leaflet et OpenStreetMap
- Visualisation des localisations en temps réel
- Statistiques (total localisations, appareils actifs, utilisateurs)
- Tableau des dernières positions

## 🔐 Sécurité et Conformité

- Chiffrement des données de localisation
- Authentification par token (Sanctum)
- Consentement utilisateur explicite
- Contrôle en un clic du partage de localisation
- CGU à accepter lors de l'inscription

## 📱 Permissions Android

L'application nécessite les permissions suivantes :
- `ACCESS_FINE_LOCATION` - Accès précis à la position GPS
- `ACCESS_COARSE_LOCATION` - Accès approximatif à la position
- `FOREGROUND_SERVICE` - Service en arrière-plan
- `POST_NOTIFICATIONS` - Notifications

## 🔄 Prochaines Étapes

- Migration vers PostgreSQL avec PostGIS pour les fonctionnalités géographiques avancées
- Implémentation du système de géofencing (alertes de zones)
- Intégration d'un système de paiement (Stripe)
- Développement des fonctionnalités B2B
- Amélioration du service en arrière-plan avec flutter_background_service

## 📝 Notes Techniques

- **Base de données** : SQLite (MVP) → PostgreSQL + PostGIS (Production)
- **Cartographie** : OpenStreetMap + Leaflet (Gratuit)
- **Framework Mobile** : Flutter (iOS & Android)
- **Framework Backend** : Laravel (PHP)

## 🤝 Contribution

Ce projet est un MVP (Minimum Viable Product) développé selon le plan d'affaires GeoGuard.

## 📄 Licence

Propriétaire - GeoGuard Project
