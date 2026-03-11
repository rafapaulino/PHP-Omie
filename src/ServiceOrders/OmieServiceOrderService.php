<?php

declare(strict_types=1);

namespace Rafapaulino\Omiephpsdk\ServiceOrders;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Rafapaulino\Omiephpsdk\Config\Config;
use Rafapaulino\Omiephpsdk\ServiceOrders\Contracts\ServiceOrderServiceInterface;
use RuntimeException;

final class OmieServiceOrderService implements ServiceOrderServiceInterface
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
    public function createServiceOrder(array $payload): array
    {
        $appKey = (string) ($this->config['omie_key'] ?? '');
        $appSecret = (string) ($this->config['omie_secret'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new RuntimeException('Missing Omie credentials in configuration.');
        }

        $requestPayload = [
            'call' => 'IncluirOS',
            'param' => [$payload],
            'app_key' => $appKey,
            'app_secret' => $appSecret,
        ];

        $response = $this->httpClient->request('POST', 'servicos/os/', [
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
}

