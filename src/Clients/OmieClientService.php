<?php

declare(strict_types=1);

namespace Rafael\Omiephpsdk\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Rafael\Omiephpsdk\Clients\Contracts\ClientServiceInterface;
use Rafael\Omiephpsdk\Config\ConfigSingleton;
use RuntimeException;

final class OmieClientService implements ClientServiceInterface
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
    public function listClients(array $filters = []): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $defaultFilters = [
            'pagina' => 1,
            'registros_por_pagina' => 50,
            'apenas_importado_api' => 'N',
        ];

        $payload = [
            'call' => 'ListarClientes',
            'param' => [array_merge($defaultFilters, $filters)],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'geral/clientes/', [
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

    /** @param array<string, mixed> $payload */
    public function createClient(array $payload): array
    {
        throw new RuntimeException('Method createClient is not implemented yet.');
    }

    public function getClient(int|string $clientId): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $payload = [
            'call' => 'ConsultarCliente',
            'param' => [[
                'codigo_cliente_omie' => $clientId,
                'codigo_cliente_integracao' => '',
            ]],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'geral/clientes/', [
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

    /** @param array<string, mixed> $payload */
    public function updateClient(int|string $clientId, array $payload): array
    {
        throw new RuntimeException('Method updateClient is not implemented yet.');
    }

    public function deleteClient(int|string $clientId): array
    {
        throw new RuntimeException('Method deleteClient is not implemented yet.');
    }
}
