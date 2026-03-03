<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Users;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Rafael\Omiephpsdk\Config\ConfigSingleton;
use Rafael\Omiephpsdk\Users\Contracts\UserServiceInterface;
use RuntimeException;

final class OmieUserService implements UserServiceInterface
{
    private ClientInterface $httpClient;

    /** @var array<string, mixed> */
    private array $config;

    public function __construct(?ClientInterface $httpClient = null)
    {
        $this->config = ConfigSingleton::getInstance()->getConfig();

        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => rtrim((string) ($this->config['omie_api'] ?? ''), '/') . '/',
            'timeout' => 30,
        ]);
    }

    /** @param array<string, mixed> $filters */
    public function listUsers(array $filters = []): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $defaultFilters = [
            'pagina' => 1,
            'registros_por_pagina' => 20,
        ];

        $payload = [
            'call' => 'ListarUsuarios',
            'param' => [array_merge($defaultFilters, $filters)],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'crm/usuarios/', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);

        $body = (string) $response->getBody();

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }
}

