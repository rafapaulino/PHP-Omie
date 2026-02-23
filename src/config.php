<?php 

return [
    'omie_api' => $_ENV['OMIE_API'] ?? 'https://app.omie.com.br/api/v1/',
    'omie_key' => $_ENV['APP_KEY'] ?? null,
    'omie_secret' => $_ENV['APP_SECRET'] ?? null
];