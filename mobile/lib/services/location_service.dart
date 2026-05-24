import 'dart:async';
import 'package:geolocator/geolocator.dart';
import 'package:mobile/services/api_service.dart';
import 'package:uuid/uuid.dart';

class LocationService {
  final ApiService _apiService = ApiService();
  final Uuid _uuid = const Uuid();
  Timer? _timer;
  bool _isRunning = false;
  String? _deviceId;

  String get deviceId => _deviceId ??= _uuid.v4();

  Future<bool> _checkPermission() async {
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      return false;
    }

    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        return false;
      }
    }

    if (permission == LocationPermission.deniedForever) {
      return false;
    }

    return true;
  }

  Future<Position?> getCurrentPosition() async {
    final hasPermission = await _checkPermission();
    if (!hasPermission) return null;

    try {
      return await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );
    } catch (e) {
      return null;
    }
  }

  Future<void> _sendLocationToServer(Position position) async {
    try {
      await _apiService.sendLocation(
        position.latitude,
        position.longitude,
        deviceId,
      );
    } catch (e) {
      print('Erreur lors de l\'envoi de la localisation: $e');
    }
  }

  void startLocationTracking() {
    if (_isRunning) return;
    _isRunning = true;

    // Envoyer la position immédiatement
    _getCurrentAndSend();

    // Envoyer la position toutes les 5 minutes
    _timer = Timer.periodic(const Duration(minutes: 5), (timer) {
      _getCurrentAndSend();
    });
  }

  Future<void> _getCurrentAndSend() async {
    final position = await getCurrentPosition();
    if (position != null) {
      await _sendLocationToServer(position);
    }
  }

  void stopLocationTracking() {
    if (!_isRunning) return;
    _isRunning = false;
    _timer?.cancel();
    _timer = null;
  }

  bool get isRunning => _isRunning;
}
