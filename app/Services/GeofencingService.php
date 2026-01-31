<?php

namespace App\Services;

class GeofencingService
{
    /**
     * Hitung jarak antara dua koordinat menggunakan Haversine formula
     * Mengembalikan jarak dalam meter
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadiusMeters = 6371000; // Earth radius in meters

        $latFrom = deg2rad($lat1);           // φ1
        $lonFrom = deg2rad($lon1);           // λ1
        $latTo = deg2rad($lat2);             // φ2
        $lonTo = deg2rad($lon2);             // λ2

        $latDelta = $latTo - $latFrom;       // Δφ
        $lonDelta = $lonTo - $lonFrom;       // Δλ

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadiusMeters;
    }

    /**
     * Cek apakah user berada dalam geofence kantor
     */
    public static function isWithinOfficeGeofence($userLat, $userLon)
    {
        $officeLat = config('attendance.office_latitude', -6.9248406);
        $officeLon = config('attendance.office_longitude', 107.6586951);
        $radius = config('attendance.geofence_radius_meters', 400);

        $distance = self::calculateDistance($userLat, $userLon, $officeLat, $officeLon);

        return $distance <= $radius;
    }

    /**
     * Get office coordinates dan radius
     */
    public static function getOfficeGeofence()
    {
        return [
            'latitude' => config('attendance.office_latitude', -6.9248406),
            'longitude' => config('attendance.office_longitude', 107.6586951),
            'radius' => config('attendance.geofence_radius_meters', 400),
        ];
    }
}
