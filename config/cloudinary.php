<?php

/*
|--------------------------------------------------------------------------
| Configuración Manual de Cloudinary
|--------------------------------------------------------------------------
|
| Este script toma tu CLOUDINARY_URL y la divide manualmente.
| Esto soluciona el error "Undefined array key 'cloud'" porque le entregamos
| los datos (cloud_name, api_key, secret) ya separados.
|
*/

$url = env('CLOUDINARY_URL');
$cloud_name = null;
$api_key = null;
$api_secret = null;

// Si la URL existe, la parseamos nosotros mismos
if ($url) {
    $parsed = parse_url($url);
    if ($parsed) {
        $cloud_name = $parsed['host'] ?? null;
        $api_key = $parsed['user'] ?? null;
        $api_secret = $parsed['pass'] ?? null;
    }
}

return [
    'cloud_url' => $url,
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
    
    // AQUÍ ESTÁ EL ARREGLO QUE FALTABA Y CAUSABA EL ERROR
    'cloud' => [
        'cloud_name' => $cloud_name,
        'api_key'    => $api_key,
        'api_secret' => $api_secret,
    ],
    
    'url' => [
        'secure' => true // Forzar siempre HTTPS
    ]
];