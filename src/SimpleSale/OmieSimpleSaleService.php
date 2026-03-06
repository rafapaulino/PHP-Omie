<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\SimpleSale;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use Rafapaulino\Omiephpsdk\Config\Config;
use Rafapaulino\Omiephpsdk\SimpleSale\Contracts\SimpleSaleServiceInterface;
use RuntimeException;

final class OmieSimpleSaleService implements SimpleSaleServiceInterface
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

    /** @param array<string, mixed> $payload */
    public function addOrder(array $payload): array
    {
        $this->validateAddOrderPayload($payload);

        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $requestPayload = [
            'call' => 'AdicionarPedido',
            'param' => [$payload],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'produtos/pedidovenda/', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $requestPayload,
        ]);

        $body = (string) $response->getBody();

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /** @param array<string, mixed> $filters */
    public function listOrders(array $filters = []): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $defaultFilters = [
            'pagina' => 1,
            'registros_por_pagina' => 100,
            'apenas_importado_api' => 'N',
        ];

        $requestPayload = [
            'call' => 'ListarPedidos',
            'param' => [array_merge($defaultFilters, $filters)],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'produtos/pedido/', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $requestPayload,
        ]);

        $body = (string) $response->getBody();

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /** @param array<string, mixed> $payload */
    public function addObs(array $payload): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $requestPayload = [
            'call' => 'AlterarPedFaturado',
            'param' => [$payload],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'produtos/pedido/', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $requestPayload,
        ]);

        $body = (string) $response->getBody();

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /** @param array<string, mixed> $payload */
    private function validateAddOrderPayload(array $payload): void
    {
        $requiredFields = [
            'codigo_pedido_integracao',
            'codigo_cliente',
            'codigo_cenario_impostos',
            'codigo_categoria',
            'codigo_conta_corrente',
            'itens',
        ];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $payload)) {
                throw new InvalidArgumentException(sprintf('Missing required field for addOrder: %s.', $field));
            }
        }

        if (!is_array($payload['itens']) || $payload['itens'] === []) {
            throw new InvalidArgumentException('Missing required field for addOrder: itens must be a non-empty array.');
        }

        $requiredItemFields = [
            'codigo_produto',
            'quantidade',
            'valor_unitario',
            'cfop',
            'codigo_cenario_impostos_item',
        ];

        foreach ($payload['itens'] as $index => $item) {
            if (!is_array($item)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid item at index %d for addOrder: item must be an array.', $index)
                );
            }

            foreach ($requiredItemFields as $field) {
                if (!array_key_exists($field, $item)) {
                    throw new InvalidArgumentException(
                        sprintf('Missing required item field for addOrder: itens[%d].%s.', $index, $field)
                    );
                }
            }
        }
    }
}


