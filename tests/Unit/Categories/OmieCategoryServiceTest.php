<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rafapaulino\Omiephpsdk\Categories\OmieCategoryService;


beforeEach(function (): void {
    resetConfigSingleton();
});

afterEach(function (): void {
    resetConfigSingleton();
});

it('lists categories using ListarCategorias payload with merged filters', function (): void {
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
        'categoria_cadastro' => [
            ['codigo' => '1.01.01', 'descricao' => 'Receita de Vendas'],
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
            'geral/categorias/',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'call' => 'ListarCategorias',
                    'param' => [[
                        'pagina' => 2,
                        'registros_por_pagina' => 25,
                    ]],
                    'app_key' => $key,
                    'app_secret' => $secret,
                ],
            ]
        )
        ->willReturn($response);

    $service = new OmieCategoryService($httpClient);

    $result = $service->listCategories($filters);

    expect($result)->toBeArray()
        ->and($result['pagina'])->toBe(2)
        ->and($result['categoria_cadastro'])->toHaveCount(1)
        ->and($result['categoria_cadastro'][0]['codigo'])->toBe('1.01.01');
});

it('throws when Omie credentials are missing for categories service', function (): void {
    $_ENV['APP_KEY'] = '';
    $_ENV['APP_SECRET'] = '';
    putenv('APP_KEY=');
    putenv('APP_SECRET=');

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient->expects($this->never())->method('request');

    $service = new OmieCategoryService($httpClient);

    $call = fn (): array => $service->listCategories();

    expect($call)->toThrow(RuntimeException::class, 'Missing Omie credentials in configuration.');
});



