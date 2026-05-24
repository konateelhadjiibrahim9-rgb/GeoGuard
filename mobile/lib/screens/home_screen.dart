import 'package:flutter/material.dart';
import 'package:mobile/services/api_service.dart';
import 'package:mobile/services/location_service.dart';
import 'login_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool _isSharingLocation = false;
  String _currentLocation = 'Non disponible';
  final ApiService _apiService = ApiService();
  final LocationService _locationService = LocationService();

  @override
  void initState() {
    super.initState();
    _checkLoginStatus();
  }

  Future<void> _checkLoginStatus() async {
    final isLoggedIn = await _apiService.isLoggedIn();
    if (!isLoggedIn && mounted) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const LoginScreen()),
      );
    }
  }

  Future<void> _logout() async {
    await _apiService.clearToken();
    if (mounted) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const LoginScreen()),
      );
    }
  }

  void _toggleLocationSharing() async {
    setState(() {
      _isSharingLocation = !_isSharingLocation;
    });

    if (_isSharingLocation) {
      final position = await _locationService.getCurrentPosition();
      if (position != null) {
        setState(() {
          _currentLocation = '${position.latitude.toStringAsFixed(6)}, ${position.longitude.toStringAsFixed(6)}';
        });
      }
      _locationService.startLocationTracking();
    } else {
      _locationService.stopLocationTracking();
      setState(() {
        _currentLocation = 'Non disponible';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('GeoGuard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: _logout,
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Icon(
              Icons.location_on,
              size: 100,
              color: Colors.blue,
            ),
            const SizedBox(height: 32),
            const Text(
              'Bienvenue sur GeoGuard',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            const Text(
              'Activez le partage de votre localisation pour permettre à vos proches de vous suivre en temps réel.',
              style: TextStyle(fontSize: 16),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 32),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  children: [
                    const Text(
                      'Statut du partage',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 16),
                    SwitchListTile(
                      title: const Text('Partager ma localisation'),
                      subtitle: Text(_isSharingLocation
                          ? 'Partage activé'
                          : 'Partage désactivé'),
                      value: _isSharingLocation,
                      onChanged: (value) => _toggleLocationSharing(),
                    ),
                    const SizedBox(height: 16),
                    Text(
                      'Position actuelle: $_currentLocation',
                      style: const TextStyle(fontSize: 14),
                      textAlign: TextAlign.center,
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  children: [
                    const Text(
                      'Conformité légale',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    const Text(
                      'En activant le partage, vous acceptez que votre localisation soit collectée et stockée conformément à nos CGU.',
                      style: TextStyle(fontSize: 12),
                      textAlign: TextAlign.center,
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
