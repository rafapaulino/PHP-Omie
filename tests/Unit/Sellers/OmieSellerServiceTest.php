<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafapaulino\Omiephpsdk\Sellers\OmieSellerService;


beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('creates a seller using IncluirVendedor payload', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $sellerPayload = [
        'codInt' => '123',
        'nome' => 'Joao Teste',
        'inativo' => 'N',
        'email' => 'teste@minhaempresa.com.br',
        'fatura_pedido' => 'S',
        'visualiza_pedido' => 'N',
        'comissao' => 10,
    ];

    $responseBody = json_encode([
        'codigo_status' => '0',
        'descricao_status' => 'Vendedor incluido com sucesso!',
    ], JSON_THROW_ON_ERROR);

    $stream = $this->createMock(StreamInterface::class);
    $stream->expects($this->once())
        ->method('__toString')
        ->willReturn($responseBody);

    $response = $this->createMock(ResponseInterface::class);
    $response->expects($this->once())
        ->method('getBody')
        ->willReturn($stream);

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->once())
        ->method('request')
        ->with(
            'POST',
            'geral/vendedores/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'IncluirVendedor',
                    'param' => [$sellerPayload],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieSellerService($httpClient);

    $result = $service->createSeller($sellerPayload);

    expect($result)->toBeArray()
        ->and($result['codigo_status'])->toBe('0')
        ->and($result['descricao_status'])->toBe('Vendedor incluido com sucesso!');
});

it('throws when Omie credentials are missing for sellers service', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieSellerService($httpClient);

    $call = fn (): array => $service->createSeller([]);

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});



