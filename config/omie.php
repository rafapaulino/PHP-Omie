<?php

return [
    'omie_api' => env('OMIE_API', 'https://app.omie.com.br/api/v1/'),
    'omie_key' => env('OMIE_APP_KEY', env('OMIE_KEY')),
    'omie_secret' => env('OMIE_APP_SECRET', env('OMIE_SECRET')),
    'timeout' => (int) env('OMIE_TIMEOUT', 30),
];