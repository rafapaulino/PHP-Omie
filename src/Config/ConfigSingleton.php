<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Config;

use Dotenv\Dotenv;

final class ConfigSingleton
{
    private static ?self $instance = null;

    /** @var array<string, mixed> */
    private array $config;

    private function __construct()
    {
        $projectRoot = dirname(__DIR__, 2);

        Dotenv::createImmutable($projectRoot)->safeLoad();

        /** @var array<string, mixed> $config */
        $config = require __DIR__ . '/../config.php';

        $this->config = $config;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** @return array<string, mixed> */
    public function getConfig(): array
    {
        return $this->config;
    }

    private function __clone(): void
    {
    }

    public function __wakeup(): void
    {
        throw new \RuntimeException('Cannot unserialize singleton');
    }
}


