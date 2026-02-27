<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafael\Omiephpsdk\Products\OmieProductService;


beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('lists products using ListarProdutos payload with merged filters', function (): void {
    $api = 'https://app.omie.com.br/api/v1/';
    $key = 'test-app-key';
    $secret = 'test-app-secret';

    $_ENV['OMIE_API'] = $api;
    $_ENV['APP_KEY'] = $key;
    $_ENV['APP_SECRET'] = $secret;

    putenv('OMIE_API=' . $api);
    putenv('APP_KEY=' . $key);
    putenv('APP_SECRET=' . $secret);

    $filters = [
        'pagina' => 2,
        'registros_por_pagina' => 25,
    ];

    $responseBody = json_encode([
        'pagina' => 2,
        'total_de_paginas' => 1,
        'produto_servico_cadastro' => [
            ['codigo_produto' => 'ABC123', 'descricao' => 'Produto 1'],
        ],
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
            'geral/produtos/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'ListarProdutos',
                    'param' => [[
                        'pagina' => 2,
                        'registros_por_pagina' => 25,
                        'apenas_importado_api' => 'N',
                        'filtrar_apenas_omiepdv' => 'N',
                    ]],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieProductService($httpClient);

    $result = $service->listProducts($filters);

    expect($result)->toBeArray()
        ->and($result['pagina'])->toBe(2)
        ->and($result['produto_servico_cadastro'])->toHaveCount(1)
        ->and($result['produto_servico_cadastro'][0]['codigo_produto'])->toBe('ABC123');
});

it('throws when Omie credentials are missing for products service', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieProductService($httpClient);

    $call = fn (): array => $service->listProducts();

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});

