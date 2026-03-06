<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\Categories;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Rafapaulino\Omiephpsdk\Categories\Contracts\CategoryServiceInterface;
use Rafapaulino\Omiephpsdk\Config\Config;
use RuntimeException;


final class OmieCategoryService implements CategoryServiceInterface
{
    private ClientInterface $httpClient;

    /** @var array<string, mixed> */
    private array $config;

    public function __construct(?ClientInterface $httpClient = null, ?array $config = null)
    {
        $this->config = Config::resolve($config);

        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => rtrim((string) ($this->config['omie_api'] ?? ''), '/') . '/',
            'timeout' => (int) ($this->config['timeout'] ?? 30),
        ]);
    }

    /** @param array<string, mixed> $filters */
    public function listCategories(array $filters = []): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $defaultFilters = [
            'pagina' => 1,
            'registros_por_pagina' => 50,
        ];

        $payload = [
            'call' => 'ListarCategorias',
            'param' => [array_merge($defaultFilters, $filters)],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'geral/categorias/', [
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



