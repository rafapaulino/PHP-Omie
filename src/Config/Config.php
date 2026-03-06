<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Config;

use Dotenv\Dotenv;

final class Config
{
    /**
     * @param array<string, mixed>|null $config
     *
     * @return array<string, mixed>
     */
    public static function resolve(?array $config = null): array
    {
        if ($config !== null) {
            return self::normalize($config);
        }

        if (function_exists('config')) {
            /** @var mixed $laravelConfig */
            $laravelConfig = config('omie');

            if (is_array($laravelConfig)) {
                return self::normalize($laravelConfig);
            }
        }

        self::loadDotenvForStandalone();

        $rawConfig = require __DIR__ . '/../config.php';

        return self::normalize($rawConfig);
    }

    private static function loadDotenvForStandalone(): void
    {
        $paths = array_unique([
            getcwd() ?: '',
            dirname(__DIR__, 2),
        ]);

        foreach ($paths as $path) {
            if ($path === '' || !is_file($path . '/.env')) {
                continue;
            }

            Dotenv::createImmutable($path)->safeLoad();
            break;
        }
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    private static function normalize(array $config): array
    {
        return [
            'omie_api' => rtrim((string) ($config['omie_api'] ?? 'https://app.omie.com.br/api/v1/'), '/') . '/',
            'omie_key' => (string) ($config['omie_key'] ?? ''),
            'omie_secret' => (string) ($config['omie_secret'] ?? ''),
            'timeout' => (int) ($config['timeout'] ?? 30),
        ];
    }
}