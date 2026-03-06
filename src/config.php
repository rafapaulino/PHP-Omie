<?php

$readEnv = static function (string $key): ?string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null) {
        return null;
    }

    return (string) $value;
};

return [
    'omie_api' => $readEnv('OMIE_API') ?? 'https://app.omie.com.br/api/v1/',
    'omie_key' => $readEnv('OMIE_APP_KEY') ?? $readEnv('OMIE_KEY') ?? $readEnv('APP_KEY'),
    'omie_secret' => $readEnv('OMIE_APP_SECRET') ?? $readEnv('OMIE_SECRET') ?? $readEnv('APP_SECRET'),
    'timeout' => (int) ($readEnv('OMIE_TIMEOUT') ?? 30),
];