<?php

return [
    // Default office coordinates (bisa di-override dari database)
    'office_latitude' => env('OFFICE_LATITUDE', -6.9248406),
    'office_longitude' => env('OFFICE_LONGITUDE', 107.6586951),
    'geofence_radius_meters' => env('GEOFENCE_RADIUS_METERS', 400),

    // File upload path
    'file_upload_path' => 'attendance-files',
    'max_file_size_mb' => 5,

    // Allowed file types untuk bukti izin/sakit
    'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png'],
];
